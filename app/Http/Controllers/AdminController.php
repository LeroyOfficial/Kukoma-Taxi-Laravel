<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

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

class AdminController extends Controller
{
    //

    public function index()
        {
            $user_count = User::where('user_type','user')->count();
            $driver_count = Driver::all()->count();
            $car_count = Car::all()->count();
            $total_revenue = TaxiTrip::where('status','completed')->sum('price');

            $drivers = Driver::take(3)->get();

            $recent_trips = TaxiTrip::take(2)->get();
            $user = User::where('user_type','user')->get();
            $address = Address::all();

            return view('admin.home',
            compact(
                'user_count','user',
                'driver_count','drivers',
                'car_count', 'address',
                'total_revenue', 'recent_trips'
                )
            );
        }

    public function drivers()
        {
            $driver_count = Driver::all()->count();
            $drivers = Driver::all();
            $trip_count = TaxiTrip::where('status','completed');

            return view('admin.drivers',compact('driver_count','drivers', 'trip_count'));
        }

    public function driverDetails($id)
        {
            $driver = Driver::find($id);
            $trip_count = TaxiTrip::where('status','completed')->where('driver_id',$driver->id)->count();
            $trips = TaxiTrip::where('status','completed')->where('driver_id',$driver->id)->get();
            $user = User::where('user_type','user')->get();
            $address = Address::all();
            $total_revenue = TaxiTrip::where('status','completed')->where('driver_id',$driver->id)->sum('price');
            $total_distance = TaxiTrip::where('status','completed')->where('driver_id',$driver->id)->sum('distance');

            return view('admin.driver_details', compact('driver','trip_count','trips','user','address','total_revenue','total_distance'));
        }

    public function getDriverLocation($id)
        {
            $driver_location = Driver::where('id',$trip->driver_id)->value('current_location');
            return response()->json([
                'driver_location' => $driver_location
            ]);
        }

    public function generateDriverReport($id)
        {
            $driver = Driver::find($id);
            $trip_count = TaxiTrip::where('status','completed')->where('driver_id',$driver->id)->count();
            $trips = TaxiTrip::where('status','completed')->where('driver_id',$driver->id)->get();
            $user = User::where('user_type','user')->get();
            $address = Address::all();
            $total_revenue = TaxiTrip::where('status','completed')->where('driver_id',$driver->id)->sum('price');
            $total_distance = TaxiTrip::where('status','completed')->where('driver_id',$driver->id)->sum('distance');


            $dompdf = new Dompdf();

            $view = view('admin.driver_report', compact('driver','trip_count','trips','user','address','total_revenue','total_distance'));

            $dompdf->loadHtml($view->render());

            $dompdf->setPaper('A4', 'portrait');

            $dompdf->render();

            $output = $dompdf->output();

            $date = Carbon::today()->format('d M Y');

            $file_name = 'Kukoma Taxi - User report for '.$driver->first_name." ".$driver->last_name." - ".$date.".pdf";

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment;filename='.$file_name,
            ];

            return response()->streamDownload(function () use ($output) {
                echo $output;
            }, $file_name, $headers);
        }

    public function NewDriver()
        {
            return view('admin.new_driver');
        }

    public function PostNewDriver(Request $request)
        {
            $driver = new Driver;

            $image = $request->picture;

            $imagename = 'Driver'.'-'.$request->first_name.'_'.$request->last_name.'_'.time().'.'.$image->getClientoriginalExtension();
            $image->move('Driver_Pics',$imagename);

            $driver->first_name = $request->first_name;
            $driver->last_name = $request->last_name;
            $driver->national_id = $request->national_id;
            $driver->phone_number = $request->phone_number;
            $driver->email = $request->email;
            $driver->car_id = 'not yet';
            $driver->current_location = 'not yet';
            $driver->picture_url = $imagename;
            $driver->status = 'offline';

            $driver->save();

            $user = new User;
            $user->name = $request->first_name .' '. $request->last_name;
            $user->user_type = 'driver';
            $user->phone = $request->phone_number;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->save();


            return redirect('admin/drivers')->with('message','New Driver Added');
        }

    public function Users()
        {
            $user_count = User::where('user_type','user')->count();
            $users = User::where('user_type','user')->get();
            $trip_count = TaxiTrip::where('status','completed');

            return view('admin.users',compact('user_count','users', 'trip_count'));
        }

    public function userDetails($id)
        {
            $user = User::find($id);
            $trip_count = TaxiTrip::where('status','completed')->where('user_id',$user->id)->count();
            $trips = TaxiTrip::where('status','completed')->where('user_id',$user->id)->get();
            $address = Address::all();
            $driver = Driver::all();
            $total_revenue = TaxiTrip::where('status','completed')->where('user_id',$user->id)->sum('price');
            $total_distance = TaxiTrip::where('status','completed')->where('user_id',$user->id)->sum('distance');

            return view('admin.user_details', compact('user','trip_count','trips','driver','address','total_revenue','total_distance'));
        }

    public function generateUserReport($id)
        {
            $user = User::find($id);
            $trip_count = TaxiTrip::where('status','completed')->where('user_id',$user->id)->count();
            $trips = TaxiTrip::where('status','completed')->where('user_id',$user->id)->get();
            $address = Address::all();
            $driver = Driver::all();
            $total_revenue = TaxiTrip::where('status','completed')->where('user_id',$user->id)->sum('price');
            $total_distance = TaxiTrip::where('status','completed')->where('user_id',$user->id)->sum('distance');

            $car = Car::all();
            $driver = Driver::all();
            $address = Address::all();


            $dompdf = new Dompdf();

            $view = view('admin.user_report', compact('user','trip_count','trips','driver','car','address','total_revenue','total_distance'));

            $dompdf->loadHtml($view->render());

            $dompdf->setPaper('A4', 'portrait');

            $dompdf->render();

            $output = $dompdf->output();

            $date = Carbon::today()->format('d M Y');
            $file_name = "Kukoma Taxi - User report for ".$user->name." - ".$date.".pdf";

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment;filename='.$file_name,
            ];

            return response()->streamDownload(function () use ($output) {
                echo $output;
            }, $file_name, $headers);

        }

    public function Cars()
        {
            $car_count = Car::count();
            $cars = Car::all();
            $category = CarCategory::all();
            return view('admin.cars', compact('cars','category','car_count'));
        }

    public function NewCar()
        {
            $categories = CarCategory::all();

            return view('admin.new_car',compact('categories'));
        }

    public function PostNewCar(Request $request)
        {
            $car = new Car;

            $image = $request->picture;

            $imagename = 'Car'.'-'.$request->first_name.'_'.$request->last_name.'_'.time().'.'.$image->getClientoriginalExtension();
            $image->move('Car_Pics',$imagename);

            $car->brand = $request->brand;
            $car->model = $request->model;
            $car->brand = $request->brand;
            $car->type = $request->type;
            $car->number_plate = $request->number_plate;
            $car->color = $request->color;
            $car->capacity = $request->capacity;
            $car->picture_url = $imagename;

            $car->save();



            return redirect('admin/cars')->with('message','New Driver Added');
        }

    public function Trips()
        {
            $trip_count = TaxiTrip::count();
            $trips = TaxiTrip::all();

            $user = User::where('user_type','user')->get();
            $driver = Driver::all();
            $address = Address::all();
            $car = Car::all();

            $total_revenue = TaxiTrip::sum('price');
            $total_distance = TaxiTrip::sum('distance');

            return view('admin.trips',compact('trip_count','trips','user','driver','address','car','total_revenue'));
        }

    public function generateTripsReport($type)
        {

            $carbon = Carbon::now();

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

            // Get trips data
            $trip_count = TaxiTrip::where('created_at', '>=', $startDate)
                ->where('created_at', '<', $endDate)->
                count();
            $trips = TaxiTrip::where('created_at', '>=', $startDate)
                ->where('created_at', '<', $endDate)->get();

            $user = User::where('user_type','user')->get();
            $driver = Driver::all();
            $address = Address::all();
            $car = Car::all();

            $total_revenue = TaxiTrip::where('created_at', '>=', $startDate)
                ->where('created_at', '<', $endDate)->
                sum('price');
            $total_distance = TaxiTrip::where('created_at', '>=', $startDate)
                ->where('created_at', '<', $endDate)->
                sum('distance');


            $dompdf = new Dompdf();

            $view = view('admin.trip_report_all',
                compact(
                    'trip_count','trips','user',
                    'driver','address','car',
                    'total_revenue','type',
                    'startDate','endDate'
                    )
                );

            $dompdf->loadHtml($view->render());

            $dompdf->setPaper('A4', 'portrait');

            $dompdf->render();

            $output = $dompdf->output();

            $date = Carbon::today()->format('d M Y');
            $file_name = "Kukoma Taxi - All trip report for ".$type." - ".$date.".pdf";

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment;filename='.$file_name,
            ];

            return response()->streamDownload(function () use ($output) {
                echo $output;
            }, $file_name, $headers);

        }

    public function Addresses()
        {
            $address_count = Address::count();
            $addresses = Address::all();

            $trip = TaxiTrip::where('status','completed')->get();

            return view('admin.addresses', compact('address_count','addresses','trip'));
        }

    public function NewAddress()
        {
            return view('admin.new_address');
        }

    public function PostNewAddress(Request $request)
        {
            $address = new Address;

            $address->name = $request->name;
            $address->latitude = $request->latitude;
            $address->longitude = $request->longitude;

            $address->save();

            return redirect('admin/addresses')->with('message','new address added successfully');
        }
}
