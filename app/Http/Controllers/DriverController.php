<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\TaxiTrip;
use App\Models\TaxiReview;

use App\Models\Address;

use App\Models\Driver;
use App\Models\Car;
use App\Models\CarCategory;

use PDF;
use Dompdf\Dompdf;

use Carbon\Carbon;

class DriverController extends Controller
{
    //

    public function index()
        {
            $driver_id = Driver::where('email',Auth::user()->email)->value('id');

            $checkIfTrip = TaxiTrip::where('driver_id', $driver_id)
            ->where('status','!=','pending')
            ->where('status','!=','waiting for driver')
            ->where('status','!=','completed')
            ->first();

            if($checkIfTrip)
                {
                    return redirect('driver/trip_progress/'.$checkIfTrip->id)->with('message','you have a trip in progress');
                }

            $car = Car::all();
            $drivers = Driver::all();
            $driver = Driver::find($driver_id);
            $address = Address::all();

            $trip_count = TaxiTrip::where('driver_id',$driver_id)->where('status','completed')->count();
            $trips = TaxiTrip::where('driver_id',$driver_id)->where('status','completed')->get();

            return view('driver.home', compact('trip_count','trips','driver','drivers','car','address'));
        }

    public function checkOnlineStatus()
        {
            $driver = Driver::where('email',Auth::user()->email)->first();

            return response()->json([
                'success' => true,
                'driver_status' => $driver->status,
            ]);
        }

    public function goOnline()
        {
            $driver = Driver::where('email',Auth::user()->email)->first();
            $driver->status = 'online';
            $driver->save();

            return response()->json([
                'success' => true,
                'message' => "gone online successfully!",
            ]);
        }

    public function goOffline()
        {
            $driver = Driver::where('email',Auth::user()->email)->first();
            $driver->status = 'offline';
            $driver->save();

            return response()->json([
                'success' => true,
                'message' => "gone offline successfully!",
            ]);
        }

    public function checkForTrips()
        {
            $driver_id = Driver::where('email',Auth::user()->email)->value('id');
            $trip = TaxiTrip::where('driver_id',$driver_id)->where('status','waiting for driver')->first();

            if($trip)
                {
                    return response()->json([
                        'success' => true,
                        'trip' => [
                            'id' => $trip->id,
                            'pickup' => json_decode($trip->pickup, true),
                            'destination' => [
                                'name' => Address::where('id',$trip->destination)->value('name'),
                                'lat' => Address::where('id',$trip->destination)->value('latitude'),
                                'long' => Address::where('id',$trip->destination)->value('longitude'),
                                ],
                            'distance' => $trip->distance,
                            'duration' => $trip->duration,
                            'status' => $trip->status,
                            'remaining' => json_decode($trip->remaining, true),
                        ]
                    ]);
                }
            else{
                return response()->json([
                    'success' => true,
                    'message' => 'not trip booking found'
                ]);
            }
        }

    public function tripAccept($id,Request $request)
        {
            $trip = TaxiTrip::find($id);

            $trip->status = "driver is coming";
            $trip->save();

            return redirect('driver/trip_progress/'.$trip->id);
        }

    public function tripProgress($id)
        {
            $trip = TaxiTrip::find($id);
            if(!$trip)
                {
                    return redirect('driver/dashboard');
                }

            if($trip->status == 'completed' || $trip->status == 'cancelled')
                {
                    return redirect('driver/history');
                }

            $address = Address::find($trip->destination);
            $driver = Driver::find($trip->driver_id);
            $user = User::find($trip->user_id);
            $car = Car::find($trip->car_id);

            return view('driver.trip_progress', compact('driver','trip', 'address', 'user', 'car'));
        }

    public function getTripDetails($id)
        {
            $driver_id = Driver::where('email',Auth::user()->email)->value('id');
            $trip = TaxiTrip::where('driver_id',$driver_id)
                ->where('status','!=','pending')
                ->where('status','!=','waiting for driver')
                ->where('status','!=','completed')
                ->first();

            if($trip)
                {
                    return response()->json([
                        'success' => true,
                        'trip' => [
                            'id' => $trip->id,
                            'user' => [
                                'name' => User::find($trip->user_id)->value('name'),
                            ],
                            'driver_id' => $trip->driver_id,
                            'pickup' => json_decode($trip->pickup, true),
                            'destination' => [
                                'name' => Address::where('id',$trip->destination)->value('name'),
                                'lat' => Address::where('id',$trip->destination)->value('latitude'),
                                'long' => Address::where('id',$trip->destination)->value('longitude'),
                                ],
                            'distance' => $trip->distance,
                            'duration' => $trip->duration,
                            'status' => $trip->status,
                            'remaining' => json_decode($trip->remaining, true),
                        ]
                    ]);
                }
            else{
                return response()->json([
                    'success' => true,
                    'message' => 'not trip booking found'
                ]);
            }
        }

    public function tripArrivedAtPickupPoint($id)
        {
            $trip = TaxiTrip::find($id);
            $trip->status = 'arrived at pickup point';
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "Trip Status modified  to 'arrived at pickup point' successfully!",
            ]);
        }

    public function getDistanceToPickupPoint($id)
        {
            $trip = TaxiTrip::find($id);
            $remaining = $trip->remaining;
            return response()->json([
                'success' => true,
                'remaining' => $remaining
            ]);
        }

    public function updateDriverLocation(Request $request)
        {
            $lat = $request->query('lat');
            $long = $request->query('long');

            $driver_location = '{"lat":'.$lat.',"long":'.$long.',"address":"null","last_update":"'.Carbon::now().'"}';

            $driver = Driver::where('email',Auth::user()->email)->first();
            $driver->current_location = $driver_location;
            $driver->save();

            return response()->json([
                'success' => true,
                'message' => "your location in DB has been updated successfully!",
            ]);
        }


    public function updateDistanceToPickupPoint($id, Request $request)
        {
            $trip = TaxiTrip::find($id);
            $distance = $request->query('distance');
            $time = $request->query('time');

            $old_remaining = json_decode($trip->remaining);

            // $new_remaining = $old_remaining;

            $remaining = '{"driver":{"distance":'.$distance.',"time":'.$time.'},"destination":{"distance":5,"time":5}}';

            $trip->remaining = $remaining;
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "distance to pickup in DB has been updated successfully!",
            ]);
        }

    public function UpdateLocationAdnDistanceToPickupPoint($id, Request $request)
        {
            $trip = TaxiTrip::find($id);

            $driver_location = $request->query('driver_location');

            // updateDriverLocation($driver_location);
            $coordinates = $request->query('coordinates');

            $driver_location = '{"lat":'.$coordinates->lat.',"long":'.$coordinates->long.',"address":'."null".',"last_update":'.Carbon::now().'}';

            $driver = Driver::where('id',$trip->id)->first();
            $driver->current_location = $driver_location;
            $driver->save();

            // updateDistanceToPickupPoint($driver_location);
            $remaining = $request->query('remaining');

            $trip->remaining = $remaining;
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "Successfully updated location and distance to pickup point!",
            ]);
        }

    public function checkTripStatus($id)
        {
            $trip = TaxiTrip::find($id);

            if($trip->status == 'pending')
                {
                    return response()->json([
                        'success' => true,
                        'message' => "trip is pending",
                    ]);
                }

            if($trip->status == 'waiting for driver')
                {
                    return response()->json([
                        'success' => true,
                        'message' => "waiting for driver",
                    ]);
                }

            if($trip->status == 'driver is coming')
                {
                    return response()->json([
                        'success' => true,
                        'message' => "driver is coming",
                    ]);
                }

            if($trip->status == 'arrived at pickup point')
                {
                    return response()->json([
                        'success' => true,
                        'message' => "arrived at pickup point",
                    ]);
                }

            if($trip->status == 'going to destination')
                {
                    return response()->json([
                        'success' => true,
                        'message' => "going to destination",
                    ]);
                }

            if($trip->status == 'arrived at destination')
                {
                    return response()->json([
                        'success' => true,
                        'message' => "arrived at destination",
                    ]);
                }
        }

    public function arrivedAtPickupPoint($id)
        {
            $trip = TaxiTrip::find($id);
            $trip->status = "arrived at pickup point";
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "trip status in db updated to 'arrived at pickup point' successfully!",
            ]);
        }

    public function startGoingToDestination($id)
        {
            $trip = TaxiTrip::find($id);
            $trip->status = "going to destination";
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "trip status in db updated to 'going to destination' successfully!",
            ]);
        }

    public function updateDistanceToDestination($id, Request $request)
        {
            $trip = TaxiTrip::find($id);
            $distance = $request->query('distance');
            $time = $request->query('time');

            $old_remaining = json_decode($trip->remaining);

            // $new_remaining = $old_remaining;

            $remaining = '{"driver":{"distance":0,"time":0},"destination":{"distance":'.$distance.',"time":'.$time.'}}';

            $trip->remaining = $remaining;
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "distance to pickup in DB has been updated successfully!",
            ]);
        }

    public function arrivedAtDestination($id)
        {
            $trip = TaxiTrip::find($id);
            $trip->status = "arrived at destination";
            $trip->save();

            $driver = Driver::where('id',$trip->driver_id)->first();
            $driver->current_location = 'online';
            $driver->save();

            // return response()->json([
            //     'success' => true,
            //     'message' => "trip status in db updated to 'arrived at destination' successfully!",
            // ]);

            return redirect('driver/history');
        }

        public function history()
        {
            $driver = Driver::where('email',Auth::user()->email)->first();
            $car = Car::all();
            $user = User::where('user_type','user')->get();
            $address = Address::all();

            $historyCount = TaxiTrip::where('driver_id',$driver->id)->where('status','completed')->count();
            $history = TaxiTrip::where('driver_id',$driver->id)->where('status','completed')->get();

            $total_spent = TaxiTrip::where('driver_id',$driver->id)->where('status','completed')->sum('price');

            return view('driver.history', compact('historyCount', 'history', 'driver', 'user' , 'car', 'address','total_spent'));
        }

    public function generateReport($type)
        {
            $driver = Driver::where('email',Auth::user()->email)->first();

            $user = User::where('user_type','user')->get();

            $endDate = Carbon::now();

            if($type == 'today')
                {
                    $startDate = Carbon::today();
                }
            if($type == 'weekly')
                {
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                }
            if($type == 'monthly')
                {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                }
            if($type == 'all')
                {
                    $startDate = $user->created_at;
                }

            $endDate = Carbon::now();
            // Get trips data for the week
            $trips = TaxiTrip::where('driver_id',$driver->id)->where('status','completed')->where('created_at', '>=', $startDate)
                ->where('created_at', '<', $endDate)
                ->get();

            $total_spent = TaxiTrip::where('driver_id',$driver->id)
                                    ->where('status','completed')
                                    ->where('status','completed')
                                    ->where('created_at', '>=', $startDate)
                                    ->where('created_at', '<', $endDate)
                                    ->sum('price');


            $car = Car::all();
            $address = Address::all();

            $dompdf = new Dompdf();

            $view = view('driver.trip_report', compact('driver', 'type', 'trips', 'user' , 'car', 'address', 'total_spent','startDate', 'endDate'));

            $dompdf->loadHtml($view->render());

            $dompdf->setPaper('A4', 'portrait');

            $dompdf->render();

            $output = $dompdf->output();

            $date = Carbon::today()->format('d M Y');

            $file_name = "Kukoma Taxi - Driver - ".$driver->first_name." ".$driver->last_name."'s ".$type." report - ".$date.".pdf";

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment;filename='.$file_name,
            ];

            return response()->streamDownload(function () use ($output) {
                echo $output;
            }, $file_name, $headers);

        }
}
