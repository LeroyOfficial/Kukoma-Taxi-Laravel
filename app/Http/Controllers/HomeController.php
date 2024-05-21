<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Car;
use App\Models\CarCategory;


class HomeController extends Controller
{
    //
    public function index()
        {

        }

    public function booking()
		{
            if(Auth::id())
                {
                    return redirect('user/booking');
                }
            else
                {
                    return redirect('/login');
                }
        }

    public function Cars()
        {
            $car_count = Car::count();
            $car_categories = CarCategory::all();
            $cars = Car::all();

            return view('user.cars', compact('car_count','car_categories', 'cars'));
        }
}
