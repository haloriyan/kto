<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('visitor.loginPage');
});

Route::get('auth/{token?}/{redirectTo?}', "VisitorController@auth")->name('visitor.authorize');
Route::get('login', "VisitorController@loginPage")->name('visitor.loginPage');
Route::get('register', "VisitorController@registerPage")->name('visitor.registerPage');
Route::post('login', "VisitorController@login")->name('visitor.login');
Route::post('register', "VisitorController@register")->name('visitor.register');
Route::get('logout', "VisitorController@logout")->name('visitor.logout');

Route::group(['middleware' => "Visitor"], function () {
    Route::get('home', "VisitorController@history")->name('visitor.home');
    Route::get('claim', "VisitorController@claim")->name('visitor.claim');
});

Route::group(['prefix' => "admin"], function () {
    Route::get('login', "AdminController@loginPage")->name('admin.loginPage');
    Route::post('login', "AdminController@login")->name('admin.login');
    Route::get('logout', "AdminController@logout")->name('admin.logout');
    Route::get('/', function () {
        return redirect()->route('admin.loginPage');
    });

    Route::group(['middleware' => "Admin"], function () {
        Route::get('dashboard', "AdminController@dashboard")->name('admin.dashboard');
        Route::get('exhibitor', "AdminController@exhibitor")->name('admin.exhibitor');
        Route::get('settings', "AdminController@settings")->name('admin.settings');
        Route::post('settings/update', "AdminController@updateSettings")->name('admin.updateSettings');
        Route::get('visitting', "AdminController@visitting")->name('admin.visitting');
        Route::get('appointment', 'AdminController@appointment')->name('admin.appointment');

        Route::group(['prefix' => "kmtm-user"], function () {
            Route::get('/', "AdminController@kmtmUser")->name('admin.kmtmUser');
            Route::get('/export', "AdminController@kmtmUserExport")->name('admin.kmtmUser.export');
            Route::get('/{id}/eligible', "AdminController@kmtmEligible")->name('admin.kmtmEligible');
            Route::get('/{id}/detail', "AdminController@kmtmDetail")->name('admin.kmtmDetail');
        });

        Route::group(['prefix' => "admin"], function () {
            Route::get('admin', "AdminController@admin")->name('admin.admin');
            Route::post('store', "AdminController@store")->name('admin.admin.store');
            Route::post('delete', "AdminController@delete")->name('admin.admin.delete');
            Route::post('change-password', "AdminController@changePassword")->name('admin.admin.changePassword');
        });

        Route::group(['prefix' => "visitor"], function () {
            Route::get('/', "AdminController@visitor")->name('admin.visitor');
            Route::get('/{id}/detail', "AdminController@visitorDetail")->name('admin.visitor.detail');
            Route::get('/{id}/eligible', "VisitorController@appointmentEligible")->name('admin.visitor.eligible');
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

        Route::group(['prefix' => "claim"], function () {
            Route::get('/', "AdminController@claim")->name('admin.claim');
            Route::get('/{id}/accept', "AdminController@acceptClaim")->name('admin.claim.accept');
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