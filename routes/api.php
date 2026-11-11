
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
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
// 1. Public Authentication Routes
// ========================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// ========================================
// 2. Temporary Admin Setup Route
// ========================================

// إنشاء أول مدير فقط - مسار مؤقت محمي بمفتاح سري
Route::post('/setup-admin', [AdminController::class, 'store']);


// ========================================
// 3. Protected API Routes
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Current User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);


    // ========================================
    // 4. Destinations
    // ========================================

    Route::middleware('permission:destinations.view')->group(function () {
        Route::get('/destinations', [DestinationController::class, 'index']);
        Route::get('/destinations/{id}', [DestinationController::class, 'show']);
    });

    Route::post('/destinations', [DestinationController::class, 'store'])
        ->middleware('permission:destinations.create');

    Route::put('/destinations/{id}', [DestinationController::class, 'update'])
        ->middleware('permission:destinations.update');

    Route::delete('/destinations/{id}', [DestinationController::class, 'destroy'])
        ->middleware('permission:destinations.delete');


    // ========================================
    // 5. Buses
    // ========================================

    Route::middleware('permission:buses.view')->group(function () {
        Route::get('/buses', [BusController::class, 'index']);
        Route::get('/buses/{id}', [BusController::class, 'show']);
    });

    Route::post('/buses', [BusController::class, 'store'])
        ->middleware('permission:buses.create');

    Route::put('/buses/{id}', [BusController::class, 'update'])
        ->middleware('permission:buses.update');

    Route::delete('/buses/{id}', [BusController::class, 'destroy'])
        ->middleware('permission:buses.delete');


    // ========================================
    // 6. Bus Seats
    // ========================================

    Route::middleware('permission:bus_seats.view')->group(function () {
        Route::get('/buses/{bus_id}/seats', [BusSeatController::class, 'index']);
        Route::get('/bus-seats/{id}', [BusSeatController::class, 'show']);
    });

    Route::middleware('permission:bus_seats.manage')->group(function () {
        Route::post('/buses/{bus_id}/seats', [BusSeatController::class, 'store']);
        Route::put('/bus-seats/{id}', [BusSeatController::class, 'update']);
        Route::delete('/bus-seats/{id}', [BusSeatController::class, 'destroy']);
    });


    // ========================================
    // 7. Drivers
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
    // 8. Trips
    // ========================================

    Route::middleware('permission:trips.view')->group(function () {
        Route::get('/trips', [TripController::class, 'index']);
        Route::get('/trips/{id}', [TripController::class, 'show']);
    });

    Route::post('/trips', [TripController::class, 'store'])
        ->middleware('permission:trips.create');

    Route::put('/trips/{id}', [TripController::class, 'update'])
        ->middleware('permission:trips.update');

    Route::delete('/trips/{id}', [TripController::class, 'destroy'])
        ->middleware('permission:trips.delete');


    // ========================================
    // 9. Bookings
    // ========================================

    Route::middleware('permission:bookings.view')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::get('/bookings/{id}', [BookingController::class, 'show']);
    });

    Route::post('/bookings', [BookingController::class, 'store'])
        ->middleware('permission:bookings.create');

    Route::put('/bookings/{id}', [BookingController::class, 'update'])
        ->middleware('permission:bookings.update');

    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])
        ->middleware('permission:bookings.cancel');


    // ========================================
    // 10. Booking Passengers
    // ========================================

    Route::middleware('permission:booking_passengers.view')->group(function () {
        Route::get('/bookings/{booking_id}/passengers', [BookingPassengerController::class, 'index']);
        Route::get('/booking-passengers/{id}', [BookingPassengerController::class, 'show']);
    });

    Route::middleware('permission:booking_passengers.manage')->group(function () {
        Route::post('/bookings/{booking_id}/passengers', [BookingPassengerController::class, 'store']);
        Route::put('/booking-passengers/{id}', [BookingPassengerController::class, 'update']);
        Route::delete('/booking-passengers/{id}', [BookingPassengerController::class, 'destroy']);
    });


    // ========================================
    // 11. Trip Seats
    // ========================================

    Route::middleware('permission:trip_seats.view')->group(function () {
        Route::get('/trips/{trip_id}/seats', [TripSeatController::class, 'index']);
        Route::get('/trip-seats/{id}', [TripSeatController::class, 'show']);
    });

    Route::middleware('permission:trip_seats.manage')->group(function () {
        Route::post('/trips/{trip_id}/seats', [TripSeatController::class, 'store']);
        Route::put('/trip-seats/{id}', [TripSeatController::class, 'update']);
        Route::delete('/trip-seats/{id}', [TripSeatController::class, 'destroy']);
    });


    // ========================================
    // 12. Payments
    // ========================================

    Route::middleware('permission:payments.view')->group(function () {
        Route::get('/bookings/{booking_id}/payments', [PaymentController::class, 'index']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);

        // Accountant: Pending Payments
        Route::get('/accountant/payments/pending', [PaymentController::class, 'pending']);
    });

    Route::post('/bookings/{booking_id}/payments', [PaymentController::class, 'store'])
        ->middleware('permission:payments.create');

    // Accountant: Review Payments
    Route::put('/accountant/payments/{id}/review', [PaymentController::class, 'review'])
        ->middleware('permission:payments.verify');

});
