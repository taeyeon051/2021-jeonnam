<?php

use Kty\App\Route;

Route::get('/', 'MainController@index');
Route::get('/daejeon/bakery', 'MainController@daejeonBakery');
Route::post('/menu/check', 'MainController@menuCheck');
Route::get('/sale/event', 'MainController@saleEvent');


if (__SESSION) {
    Route::get('/logout', 'UserController@logout');
    $user = $_SESSION['user'];
    if ($user->type == "normal") {
        Route::get('/user/mypage', 'UserController@myPageNormal');
    } else if ($user->type == "rider") {
        Route::get('/user/mypage', 'UserController@myPageRider');
    } else if ($user->type == "owner") {
        Route::get('/user/mypage', 'UserController@myPageOwner');
    }
} else {
    Route::get('/login', 'UserController@login');
    Route::post('/login', 'UserController@loginProcess');
}
