<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return bcrypt("inikatasandi");
});

Route::get('auth/{token?}/{redirectTo?}', "VisitorController@auth")->name('visitor.authorize');
Route::get('login', "VisitorController@loginPage")->name('visitor.loginPage');
Route::get('register', "VisitorController@registerPage")->name('visitor.registerPage');
Route::post('login', "VisitorController@login")->name('visitor.login');
Route::post('register', "VisitorController@register")->name('visitor.register');
Route::get('logout', "VisitorController@logout")->name('visitor.logout');

Route::group(['middleware' => "Visitor"], function () {
    Route::get('home', "VisitorController@home")->name('visitor.home');
});

Route::group(['prefix' => "admin"], function () {
    Route::get('login', "AdminController@loginPage")->name('admin.loginPage');
    Route::post('login', "AdminController@login")->name('admin.login');
    Route::get('logout', "AdminController@logout")->name('admin.logout');

    Route::group(['middleware' => "Admin"], function () {
        Route::get('dashboard', "AdminController@dashboard")->name('admin.dashboard');
        Route::get('exhibitor', "AdminController@exhibitor")->name('admin.exhibitor');
        Route::get('visitting', "AdminController@visitting")->name('admin.visitting');
        Route::get('appointment', 'AdminController@appointment')->name('admin.appointment');

        Route::group(['prefix' => "visitor"], function () {
            Route::get('/', "AdminController@visitor")->name('admin.visitor');
            Route::get('/{id}/detail', "AdminController@visitorDetail")->name('admin.visitor.detail');
        });

        Route::group(['prefix' => "exhibitor"], function () {
            Route::post('store', "ExhibitorController@store")->name('exhibitor.store');
            Route::post('delete', "ExhibitorController@delete")->name('exhibitor.delete');
            Route::get('{slug}/qr', "ExhibitorController@qr")->name('exhibitor.qr');
        });

        Route::group(['prefix' => "schedule"], function () {
            Route::post('add', "ScheduleController@add")->name('schedule.add');
            Route::post('delete', "ScheduleController@delete")->name('schedule.delete');
            Route::post('edit', "ScheduleController@edit")->name('schedule.edit');
            Route::get('/', "AdminController@schedule")->name('admin.schedule');
        });
    });
});

Route::group(['prefix' => "visitor"], function () {
    // Route::get('home', "VisitorController@home")->name('visitor.home');
    Route::get('exhibitor', "VisitorController@exhibitor")->name('visitor.exhibitor');
    Route::get('make-appointment', "VisitorController@makeAppointment")->name('visitor.makeAppointment')->middleware('Visitor');
    Route::get('exhibitor/{slug}', "VisitorController@exhibitorDetail")->name('visitor.exhibitor.detail');
});

Route::group(['middleware' => "Visitor"], function () {
    Route::get('booth-scan/{boothUniqueID?}', "VisitorController@boothScan")->name('boothScan');
    Route::get('history', "VisitorController@history")->name('historyScan');
});