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

class UserController extends Controller
{
    //
    public function index()
        {
            $user_id = Auth::user()->id;

            $checkIfTrip = TaxiTrip::where('user_id', $user_id)->where('status','!=','cancelled')->where('status','!=','completed')->first();

            if($checkIfTrip)
                {
                    return redirect('user/trip_progress/'.$checkIfTrip->id)->with('message','you have a trip in progress');
                }

            $car = Car::all();
            $driver = Driver::all();
            $address = Address::all();

            $trips = TaxiTrip::where('user_id',$user_id)->where('status','completed')->get();

            return view('user.home', compact('trips','driver','car','address'));
        }

    public function cars()
        {
            $car_count = Car::count();
            $car_categories = CarCategory::all();
            $cars = Car::all();

            return view('user.cars', compact('car_count','car_categories', 'cars'));
        }

    public function booking()
        {
            $car_categories = CarCategory::all();
            $cars = Car::all();
            return view('user.booking', compact('car_categories','cars'));
        }

    public function searchAddress(Request $request)
        {
            $searchTerm = $request->query('name');

            $results = Address::where('name', 'like', "%{$searchTerm}%")
                ->select('name', 'latitude', 'longitude')
                ->get();

            if($results->count() > 0)
                {
                    return response()->json([
                        'success' => true,
                        'results' => $results,
                    ]);
                }
            else
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'no matching address found.. tell admin to add more addresses',
                    ]);
                }
        }

    public function fetchAvailableDrivers()
        {
            $drivers = Driver::where('status','online')->where('current_location','!=','not yet')->get();

            if($drivers->count() > 0)
                {
                    return response()->json([
                        'success' => true,
                        'drivers' => $drivers,
                    ]);
                }
            else
            {
                return response()->json([
                    'success' => true,
                    'message' => "No drivers available",
                ]);
            }
        }

    public function bookDriver($id)
        {
            $driver = Driver::find($id);
            $driver->status = 'booked';
            $driver->save();

            return response()->json([
                'success' => true,
                'message' => "Driver Status modified  to 'booked' successfully!",
            ]);
        }

    public function newBooking(Request $request)
        {
            $user_id = Auth::user()->id;

            $driver_id = $request->closest_driver_id;

            if($driver_id)
                {
                    $driver = Driver::find($driver_id);
                    $driver->status = 'booked';
                    $driver->save();
                }
            else
                {
                    $driver = Driver::where('status','online')->where('current_location','!=','not yet')->first();

                    if($driver)
                        {
                            $driver->status = 'booked';
                            $driver->save();

                            $driver_id = $driver->id;
                        }
                    else
                        {
                            return redirect()->back()->with('error','no driver available');
                        }
                }

            $category = CarCategory::where('name',$request->car_type_name)->first();

            $car= Car::where('type',$category->id)->inRandomOrder()->first();

            $address = Address::where('name',$request->destination)->first();

            $booking = new TaxiTrip;

            $booking->user_id = $user_id;
            $booking->car_type = $category->id;
            $booking->driver_id = $driver_id;
            $booking->car_id = $car->id;
            $booking->pickup = $request->pickup;
            $booking->destination = $address->id;
            $booking->distance = $request->distance;
            $booking->price = $request->price;
            $booking->duration = $request->duration;
            $booking->final_duration = $request->duration;
            $booking->remaining = '{ "driver":{"distance":5,"time":5},"destination":{"distance":5,"time":5}}';
            $booking->status = 'pending';
            $booking->save();

            $trip = TaxiTrip::where('user_id', $user_id)->where('status','pending')->first();

            return redirect('user/trip_progress/'.$trip->id);
        }

    public function tripProgress($id)
        {
            $trip = TaxiTrip::find($id);
            if(!$trip)
                {
                    return redirect('user/booking');
                }

            $address = Address::find($trip->destination);
            $driver = Driver::find($trip->driver_id);
            $car = Car::find($trip->car_id);

            return view('user.trip_progress', compact('trip', 'address', 'driver', 'car'));
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

    public function tripAccept($id)
        {
            $trip = TaxiTrip::find($id);
            $trip->status = 'waiting for driver';
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "Trip Status modified  to 'waiting for driver' successfully!",
            ]);
        }

    public function tripCancel($id)
        {
            $trip = TaxiTrip::find($id);
            $trip->status = 'cancelled';
            $trip->save();

            $driver = Driver::where('id',$trip->driver_id)->first();
            $driver->status = 'online';
            $driver->save();

            return redirect('user/booking');
            // return response()->json([
            //     'success' => true,
            //     'message' => "Trip Status modified  to 'cancelled' successfully!",
            // ]);
        }

    public function checkDriverLocation($id)
        {
            $trip = TaxiTrip::find($id);
            $driver_location = Driver::where('id',$trip->driver_id)->value('current_location');
            $remaining = $trip->remaining;
            return response()->json([
                'driver_location' => $driver_location,
                'remaining' => $remaining
            ]);
        }

    public function tripStartGoing($id)
        {
            $trip = TaxiTrip::find($id);
            $trip->status = 'going to destination';
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "Trip Status modified  to 'going to destination' successfully!",
            ]);
        }

    public function goingToDestination($id)
        {
            $trip = TaxiTrip::find($id);
            $driver_location = Driver::where('id',$trip->driver_id)->value('current_location');
            $remaining = $trip->remaining;
            return response()->json([
                'driver_location' => $driver_location,
                'remaining' => $remaining
            ]);
        }

    public function tripArrived($id)
        {
            $trip = TaxiTrip::find($id);
            $trip->status = 'arrived at destination';
            $trip->save();

            return response()->json([
                'success' => true,
                'message' => "Trip Status modified  to 'arrived at destination' successfully!",
            ]);
        }

    public function tripPostReview($id, Request $request)
        {
            $user_id = Auth::user()->id;

            $trip = TaxiTrip::find($id);
            $trip->status = 'completed';
            $trip->save();

            $driver = Driver::find($trip->driver_id);
            $driver->status = 'online';
            $driver->save();

            $review = new TaxiReview;
            $review->trip_id = $id;
            $review->user_id = $user_id;
            $review->driver_id = $trip->driver_id;
            $review->message = $request->message;
            $review->save();

            return redirect('user/history')->with('message','trip completed');
        }

    public function history()
        {
            $user_id = Auth::user()->id;
            $car = Car::all();
            $driver = Driver::all();
            $address = Address::all();

            $history = TaxiTrip::where('user_id',$user_id)->where('status','completed')->get();

            $total_spent = TaxiTrip::where('user_id',$user_id)->where('status','completed')->sum('price');

            return view('user.history', compact('history', 'driver' , 'car', 'address','total_spent'));
        }

    public function generateReport($type)
        {
            $user = Auth::user();

            // $user = User::find($user_id);

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
            $trips = TaxiTrip::where('user_id',$user->id)->where('status','completed')->where('created_at', '>=', $startDate)
                ->where('created_at', '<', $endDate)
                ->get();

            $total_spent = TaxiTrip::where('user_id',$user->id)
                                    ->where('status','completed')
                                    ->where('status','completed')
                                    ->where('created_at', '>=', $startDate)
                                    ->where('created_at', '<', $endDate)
                                    ->sum('price');


            $car = Car::all();
            $driver = Driver::all();
            $address = Address::all();

            $dompdf = new Dompdf();

            $view = view('user.trip_report', compact('user', 'type', 'trips', 'driver' , 'car', 'address', 'total_spent','startDate', 'endDate'));

            $dompdf->loadHtml($view->render());

            $dompdf->setPaper('A4', 'portrait');

            $dompdf->render();

            $output = $dompdf->output();

            $date = Carbon::today()->format('d M Y');

            $file_name = "Kukoma Taxi - ".$user->name."'s ".$type." report - ".$date.".pdf";

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment;filename='.$file_name,
            ];

            return response()->streamDownload(function () use ($output) {
                echo $output;
            }, $file_name, $headers);

        }
}
