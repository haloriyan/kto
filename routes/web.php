<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => "visitor"], function () {
    Route::get('auth/{token?}/{redirectTo?}', "VisitorController@auth")->name('visitor.authorize');
    Route::get('login', "VisitorController@loginPage")->name('visitor.loginPage');
    Route::get('register', "VisitorController@registerPage")->name('visitor.registerPage');
    Route::post('login', "VisitorController@login")->name('visitor.login');
    Route::post('register', "VisitorController@register")->name('visitor.register');
    Route::get('logout', "VisitorController@logout")->name('visitor.logout');
    Route::get('/', function () {
        return redirect()->route('visitor.loginPage');
    });
});

Route::get('switch-lang/{lang}', "VisitorController@switchLang")->name('switchLang');

Route::group(['middleware' => "Visitor"], function () {
    Route::get('home', "VisitorController@history")->name('visitor.home');
    Route::get('claim', "VisitorController@claim")->name('visitor.claim');
    Route::get('exclusive/claim', "VisitorController@claimExclusiveGift")->name('visitor.claimExclusiveGift');
});

Route::group(['prefix' => "kmtm"], function () {
    Route::get('/', function () {
        return redirect()->route('kmtm.loginPage');
    });
    Route::get('login', "BuyerController@loginPage")->name('kmtm.loginPage');
    Route::post('login', "BuyerController@login")->name('kmtm.login');
    Route::group(['middleware' => "Kmtm"], function () {
        Route::get('home', "BuyerController@home")->name('kmtm.home');
        Route::get('make-appointment', "BuyerController@makeAppointment")->name('kmtm.makeAppointment');
        Route::post('cancel-appointment', "BuyerController@cancelAppointment")->name('kmtm.cancel');
    });
});

Route::group(['prefix' => "kmte"], function () {
    Route::get('/', function () {
        return redirect()->route('kmte.loginPage');
    });
    Route::get('login', "KmteController@loginPage")->name('kmte.loginPage');
    Route::post('login', "KmteController@login")->name('kmte.login');
    Route::get('register', "KmteController@registerPage")->name('kmte.registerPage');
    Route::post('register', "KmteController@register")->name('kmte.register');
    Route::get('register/done', "KmteController@registerDone")->name('kmte.registerDone');
});

Route::group(['prefix' => "admin"], function () {
    Route::get('login', "AdminController@loginPage")->name('admin.loginPage');
    Route::post('login', "AdminController@login")->name('admin.login');
    Route::get('logout', "AdminController@logout")->name('admin.logout');
    Route::get('/', function () {
        return redirect()->route('admin.loginPage');
    });

    Route::group(['middleware' => "Admin"], function () {
        Route::get('migrate-kmtm-buyer', "AdminController@migrateKmtmBuyer");
        Route::get('dashboard', "AdminController@dashboard")->name('admin.dashboard');
        Route::get('exhibitor', "AdminController@exhibitor")->name('admin.exhibitor');
        Route::get('settings', "AdminController@settings")->name('admin.settings');
        Route::post('settings/update', "AdminController@updateSettings")->name('admin.updateSettings');
        Route::get('visitting', "AdminController@visitting")->name('admin.visitting');
        Route::get('appointment', 'AdminController@appointment')->name('admin.appointment');
        Route::get('appointment/export', 'AdminController@appointmentExport')->name('admin.appointment.export');

        Route::group(['prefix' => "kmtm-user"], function () {
            Route::get('/', "AdminController@kmtmUser")->name('admin.kmtmUser');
            Route::get('/export', "AdminController@kmtmUserExport")->name('admin.kmtmUser.export');
            Route::get('/{id}/eligible', "AdminController@kmtmEligible")->name('admin.kmtmEligible');
            Route::get('/{id}/detail', "AdminController@kmtmDetail")->name('admin.kmtmDetail');
        });

        Route::group(['prefix' => "kmte"], function () {
            Route::get('export', "AdminController@kmteUserExport")->name('admin.kmteUser.export');
            Route::get('/', "AdminController@kmteUser")->name('admin.kmteUser');
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

        Route::group(['prefix' => "seller"], function () {
            Route::post('store', "SellerController@store")->name('seller.store');
            Route::post('update', "SellerController@update")->name('seller.update');
            Route::post('delete', "SellerController@delete")->name('seller.delete');
            Route::get('create', "SellerController@create")->name('seller.create');
            Route::get('/{id}/edit', "SellerController@edit")->name('seller.edit');
            Route::get('update-db', "SellerController@updateDb");
            Route::get('regenerate', "AdminController@regenerateSellerQR")->name('admin.seller.regenerate');
            Route::get('/', "AdminController@seller")->name('admin.seller');
            Route::get('{id}/qr', "SellerController@qr")->name('seller.qr');
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
            Route::get('/export', "AdminController@claimExport")->name('admin.claim.export');
            Route::get('/{id}/accept', "AdminController@acceptClaim")->name('admin.claim.accept');
        });
        Route::group(['prefix' => "exclusive-claim"], function () {
            Route::get('/{id}/accept', "AdminController@acceptExclusiveClaim")->name('admin.exclusiveGift.claim.accept');
            Route::get('/export', "AdminController@exclusiveClaimExport")->name('admin.exclusiveGift.export');
            Route::get('/', "AdminController@exclusiveClaim")->name('admin.exclusiveGift.claim');
        });
        Route::group(['prefix' => "techno-claim"], function () {
            Route::get('/', "AdminController@technoClaim")->name('admin.technoGift.claim');
            Route::get('/export', "AdminController@technoClaimExport")->name('admin.technoGift.export');
            Route::get('/{id}/accept', "AdminController@acceptTechnoClaim")->name('admin.technoGift.claim.accept');
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