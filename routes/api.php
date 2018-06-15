<?php
// 
Route::options('{all}', function () {
    $response = Response::make('');
    return $response;
})->middleware('api');

// not outh endpoint
Route::group(['middleware' => ['api']], function() {
    Route::prefix('common')->group(function () {
    });
    // prefix outh
    Route::prefix('outh')->group(function () {
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login');
        Route::post('/forgot_password', 'AuthController@forgotPassword');
        Route::post('/token', 'AuthController@getResetPasswordToken');
        Route::post('/reset_password', 'AuthController@resetPassword');
    });
    // Resource base 
    Route::resource('studios', 'StudioController')
        ->only('index', 'show');
    Route::resource('studios.comments', 'CommentController')
        ->only('index', 'show');
});

// auth endpoint
Route::group(['middleware' => ['auth:api']], function() {
    // prefix outh
    Route::prefix('outh')->group(function () {
        Route::get('/user', 'AuthController@user');
        Route::post('/logout', 'AuthController@logout');
    });
    // Resource base 
    Route::resource('user_informations', 'UserInformationController')
        ->only('index', 'show', 'update', 'destroy');
    Route::resource('studios', 'StudioController')
        ->except('index', 'show', 'edit');
    Route::resource('studios.comments', 'CommentController')
        ->except('index', 'show', 'edit');
});
