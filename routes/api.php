<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\LocationCityController;
use App\Http\Controllers\LocationDistrictController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ** Khúc này khi làm tới đâu nhớ khai báo đường dẫn API
// ** VD làm bảng locationCity thì khai báo giống vậy thì 
// ** Khi gắn link vào postman sẽ tương đương http://127.0.0.1:8000/api/locationCity
// !! Lưu ý dùng lệnh php artisan route:list để check các phương thức GET,POST...
// !! Lưu ý khi tạo dùng lệnh php artisan make:controller NameController --resource để render full các phương thức
Route::apiResource('locationCity', LocationCityController::class);

// ** Khúc này của bảng locationDistrict
Route::apiResource('locationDistrict', LocationDistrictController::class);


// ** Khúc này của bảng hotel
Route::apiResource('hotel', HotelController::class);


// ** Khúc này của bảng hotel
Route::apiResource('room', RoomController::class);

// ** Khúc này của bảng booking
Route::apiResource('booking', BookingController::class);

// ** php artisan make:controller
// ** php artisan make:model
// ** php artisan make:resource