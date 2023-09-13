<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => "visitor"], function () {
    Route::post('login', "VisitorController@login")->name('api.visitor.login');
});
Route::post('appointment-schedule', "ScheduleController@get")->name('api.AppointmentSchedule');
Route::post('kmtm-register', "VisitorController@kmtmRegister");
