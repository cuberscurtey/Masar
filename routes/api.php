
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusSeatController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPassengerController;
use App\Http\Controllers\TripSeatController;
use App\Http\Controllers\PaymentController;


// ========================================
// 1. Authentication Routes
// ========================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// ========================================
// 2. Authenticated Routes
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // ------------------------------------
    // Current User
    // ------------------------------------

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ------------------------------------
    // Logout
    // ------------------------------------

    Route::post('/logout', [AuthController::class, 'logout']);


    // ========================================
    // 3. Destinations Routes
    // Admin: Full Management
    // Customer / Trip Supervisor: Read Only
    // ========================================

    Route::middleware('role:admin,customer,trip_supervisor')->group(function () {

        Route::get('/destinations', [DestinationController::class, 'index']);
        Route::get('/destinations/{id}', [DestinationController::class, 'show']);

    });

    Route::middleware('role:admin')->group(function () {

        Route::post('/destinations', [DestinationController::class, 'store']);
        Route::put('/destinations/{id}', [DestinationController::class, 'update']);
        Route::delete('/destinations/{id}', [DestinationController::class, 'destroy']);

    });


    // ========================================
    // 4. Buses Routes
    // Admin: Full Management
    // Customer / Trip Supervisor: Read Only
    // ========================================

    Route::middleware('role:admin,customer,trip_supervisor')->group(function () {

        Route::get('/buses', [BusController::class, 'index']);
        Route::get('/buses/{id}', [BusController::class, 'show']);

    });

    Route::middleware('role:admin')->group(function () {

        Route::post('/buses', [BusController::class, 'store']);
        Route::put('/buses/{id}', [BusController::class, 'update']);
        Route::delete('/buses/{id}', [BusController::class, 'destroy']);

    });


    // ========================================
    // 5. Bus Seats Routes
    // Admin: Full Management
    // Customer / Trip Supervisor: Read Only
    // ========================================

    Route::middleware('role:admin,customer,trip_supervisor')->group(function () {

        Route::get('/buses/{bus_id}/seats', [BusSeatController::class, 'index']);
        Route::get('/bus-seats/{id}', [BusSeatController::class, 'show']);

    });

    Route::middleware('role:admin')->group(function () {

        Route::post('/buses/{bus_id}/seats', [BusSeatController::class, 'store']);
        Route::put('/bus-seats/{id}', [BusSeatController::class, 'update']);
        Route::delete('/bus-seats/{id}', [BusSeatController::class, 'destroy']);

    });


    // ========================================
    // 6. Drivers Routes
    // Admin: Full Management
    // ========================================

    Route::middleware('role:admin')->group(function () {

        Route::get('/drivers/search', [DriverController::class, 'search']);
        Route::get('/drivers', [DriverController::class, 'index']);
        Route::get('/drivers/{id}', [DriverController::class, 'show']);
        Route::post('/drivers', [DriverController::class, 'store']);
        Route::put('/drivers/{id}', [DriverController::class, 'update']);
        Route::delete('/drivers/{id}', [DriverController::class, 'destroy']);

    });


    // ========================================
    // 7. Trips Routes
    // Admin / Trip Supervisor: Full Management
    // Customer: Read Only
    // ========================================

    Route::middleware('role:admin,customer,trip_supervisor')->group(function () {

        Route::get('/trips', [TripController::class, 'index']);
        Route::get('/trips/{id}', [TripController::class, 'show']);

    });

    Route::middleware('role:admin,trip_supervisor')->group(function () {

        Route::post('/trips', [TripController::class, 'store']);
        Route::put('/trips/{id}', [TripController::class, 'update']);
        Route::delete('/trips/{id}', [TripController::class, 'destroy']);

    });


    // ========================================
    // 8. Bookings Routes
    // Customer: Booking Management
    // ========================================

    Route::middleware('role:customer')->group(function () {

        Route::get('/bookings', [BookingController::class, 'index']);
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings/{id}', [BookingController::class, 'show']);
        Route::put('/bookings/{id}', [BookingController::class, 'update']);
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

    });


    // ========================================
    // 9. Booking Passengers Routes
    // Customer: Passenger Management
    // ========================================

    Route::middleware('role:customer')->group(function () {

        Route::get('/bookings/{booking_id}/passengers', [BookingPassengerController::class, 'index']);
        Route::post('/bookings/{booking_id}/passengers', [BookingPassengerController::class, 'store']);

        Route::get('/booking-passengers/{id}', [BookingPassengerController::class, 'show']);
        Route::put('/booking-passengers/{id}', [BookingPassengerController::class, 'update']);
        Route::delete('/booking-passengers/{id}', [BookingPassengerController::class, 'destroy']);

    });


    // ========================================
    // 10. Trip Seats Routes
    // Admin / Trip Supervisor: Full Management
    // Customer: Read Only
    // ========================================

    Route::middleware('role:admin,customer,trip_supervisor')->group(function () {

        Route::get('/trips/{trip_id}/seats', [TripSeatController::class, 'index']);
        Route::get('/trip-seats/{id}', [TripSeatController::class, 'show']);

    });

    Route::middleware('role:admin,trip_supervisor')->group(function () {

        Route::post('/trips/{trip_id}/seats', [TripSeatController::class, 'store']);
        Route::put('/trip-seats/{id}', [TripSeatController::class, 'update']);
        Route::delete('/trip-seats/{id}', [TripSeatController::class, 'destroy']);

    });


    // ========================================
    // 11. Payments Routes
    // Customer: Create and View Own Payments
    // ========================================

    Route::middleware('role:customer')->group(function () {

        Route::get('/bookings/{booking_id}/payments', [PaymentController::class, 'index']);
        Route::post('/bookings/{booking_id}/payments', [PaymentController::class, 'store']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);

    });


    // ========================================
    // 12. Accountant Routes
    // Accountant: Review and Approve Payments
    // ========================================

    Route::middleware('role:accountant')->group(function () {

        // View Pending Payments
        Route::get('/accountant/payments/pending', [PaymentController::class, 'pending']);

        // Approve or Reject Payment
        Route::put('/accountant/payments/{id}/review', [PaymentController::class, 'review']);

    });

});
