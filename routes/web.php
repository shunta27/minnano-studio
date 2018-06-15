<?php
// not SPA route
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

// SPA route
Route::prefix('spa')->group(function () {
    Route::any('{all?}', function () {
        return view('spa.app');
    })
    ->where(['all' => '.*']);
});