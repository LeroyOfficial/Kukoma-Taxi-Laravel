
<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;


use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/cars', function () {
    return view('cars');
});

Route::get('/booking', [HomeController::class,'booking']);

Route::prefix('admin')->middleware('auth','IsAdmin')->group(function() {
	Route::get('dashboard', [AdminController::class,'index']);
	Route::get('drivers', [AdminController::class,'drivers']);
	Route::get('driver/{id}', [AdminController::class,'driverDetails']);
	Route::get('driver/{id}/get-driver-location', [AdminController::class,'getDriverLocation']);
	Route::get('driver/{id}/generate_report', [AdminController::class,'generateDriverReport']);
	Route::get('new_driver', [AdminController::class,'NewDriver']);
	Route::post('post_new_driver', [AdminController::class,'PostNewDriver']);

	Route::get('users', [AdminController::class,'Users']);
	Route::get('user/{id}', [AdminController::class,'userDetails']);
	Route::get('user/{id}/generate_report', [AdminController::class,'generateUserReport']);

	Route::get('cars', [AdminController::class,'Cars']);
    Route::get('new_car', [AdminController::class,'NewCar']);
	Route::post('post_new_car', [AdminController::class,'PostNewCar']);


	Route::get('trips', [AdminController::class,'Trips']);
	Route::get('trip_history/generate_report/{type}', [AdminController::class,'generateTripsReport']);

	Route::get('addresses', [AdminController::class,'Addresses']);
	Route::get('new_address', [AdminController::class,'NewAddress']);
	Route::post('post_new_address', [AdminController::class,'PostNewAddress']);

});

Route::prefix('driver')->middleware('auth','IsDriver')->group(function() {
	Route::get('dashboard', [DriverController::class,'index']);


	Route::get('check_online_status', [DriverController::class,'checkOnlineStatus']);

	Route::get('go_online', [DriverController::class,'goOnline']);
	Route::get('go_offline', [DriverController::class,'goOffline']);

    Route::get('update_location', [DriverController::class,'updateDriverLocation']);

    Route::get('check_for_trips', [DriverController::class,'checkForTrips']);

    Route::get('trip_progress/{id}', [DriverController::class,'tripProgress']);
    Route::get('trip/{id}/get-trip-details', [DriverController::class,'getTripDetails']);
    Route::get('trip/{id}/check-trip-status', [DriverController::class,'checkTripStatus']);
    Route::get('trip/{id}/check-customer-location', [DriverController::class,'checkCustomerLocation']);
    Route::get('trip/{id}/accept', [DriverController::class,'tripAccept']);
    Route::get('trip/{id}/cancel', [DriverController::class,'tripCancel']);
	Route::get('trip/{id}/post-driver-distance', [DriverController::class,'updateDistanceToPickupPoint']);
	Route::get('trip/{id}/arrived-pickup', [DriverController::class,'tripArrivedAtPickupPoint']);
	Route::get('trip/{id}/start_going', [DriverController::class,'tripStartGoing']);
    Route::get('trip/{id}/going-to-destination', [DriverController::class,'startGoingToDestination']);
	Route::get('trip/{id}/post-destination-distance', [DriverController::class,'updateDistanceToDestination']);
	Route::get('trip/{id}/arrived-destination', [DriverController::class,'arrivedAtDestination']);

    Route::get('history', [DriverController::class,'history']);
	Route::get('history/generate_report/{type}', [DriverController::class,'generateReport']);

});

Route::prefix('user')->middleware('auth','IsUser')->group(function() {
	Route::get('dashboard', [UserController::class,'index']);

	Route::get('cars', [UserController::class,'cars']);

	Route::get('booking', [UserController::class,'booking']);
    Route::get('booking/fetch-address', [UserController::class,'searchAddress']);
    Route::get('booking/fetch-available-drivers', [UserController::class,'fetchAvailableDrivers']);
    // Route::get('booking/book-driver/{id}', [UserController::class,'bookDriver']);
	Route::post('post_new_booking', [UserController::class,'newBooking']);

	Route::get('trip_progress/{id}', [UserController::class,'tripProgress']);
    Route::get('trip/{id}/check-trip-status', [UserController::class,'checkTripStatus']);
    Route::get('trip/{id}/check-driver-location', [UserController::class,'checkDriverLocation']);
    Route::get('trip/{id}/accept', [UserController::class,'tripAccept']);
    Route::get('trip/{id}/cancel', [UserController::class,'tripCancel']);
	Route::get('trip/{id}/start_going', [UserController::class,'tripStartGoing']);
    Route::get('trip/{id}/going-to-destination', [UserController::class,'goingToDestination']);
	Route::get('trip/{id}/arrived', [UserController::class,'tripArrived']);
	Route::post('trip/{id}/post_review', [UserController::class,'tripPostReview']);

	Route::get('history', [UserController::class,'history']);
	Route::get('history/generate_report/{type}', [UserController::class,'generateReport']);
	Route::get('history/generate_for_today', [UserController::class,'generateReportForToday']);
	Route::get('history/generate_for_week', [UserController::class,'generateReportForWeek']);
	Route::get('history/generate_for_month', [UserController::class,'generateReportForMonth']);
	Route::get('history/generate_for_all', [UserController::class,'generateReportForAll']);



});

Route::get('/dashboard', function () {
    if(Auth::id())
        {
            if(Auth::user()->user_type == 'user')
                {
                    return redirect('/user/dashboard');
                }
            if(Auth::user()->user_type == 'driver')
                {
                    return redirect('/driver/dashboard');
                }
            if(Auth::user()->user_type == 'admin')
                {
                    return redirect('/admin/dashboard');
                }
        }
    else
        {
            return redirect('/login');
        }
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),

//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
