<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function()
{
    Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function(){

        Route::get('/welcome' , 'DashboardController@index')->name('dashboard.index');

        // user resursce
        Route::resource('users' , 'UserController')->except('show');

        //category resurce
        Route::resource('categories' , 'CategoryController')->except('show');

        //product resurce
        Route::resource('products' , 'ProductController')->except('show');

        //Clint resurce
        Route::resource('clients' , 'ClientController')->except('show');

        Route::resource('clients.orders' , 'Client\OrderController')->except('show');

        // Order resurce
        Route::resource('orders' , 'OrderController');
        Route::get('/orders/{order}/products' , 'OrderController@products')->name('orders.products');


    });
});

Route::get('/test' , function (){
    $users = \App\Models\User::paginate(3);
    return view('test')->with('users' , $users);
});


