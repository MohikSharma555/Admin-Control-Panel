<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(Auth::check()){
        return redirect('/home');
    }
    return redirect('/login');
});

Route::get('/cache-fix',function(){
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('view:cache');
    dd("Cache Fixed Successfully");
});

Auth::routes([
    'register' => false
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'],function(){

    Route::group(['middleware' => 'subscriber'],function(){

        Route::post('/home/change-password','HomeController@changepassword');

        //Client Routes...
        Route::post('/home/client/store', 'ClientsController@store');
        Route::get('/home/client/{cid}/show', 'ClientsController@show');
        Route::post('/home/client/search','HomeController@searchclients');

        //Document Routes...
        Route::post('/home/client/document/store', 'DocumentsController@store');
        Route::get('/home/client/document/{did}/delete', 'DocumentsController@delete');

        //Note Routes...
        Route::post('/home/client/note/store', 'NotesController@store');
    });

    Route::group(['middleware' => 'admin'],function(){
        //User Routes...
        Route::post('/home/user/store','HomeController@createuser');
    });
   
});