<?php

use Kty\App\Route;

Route::get('/', 'MainController@index');
Route::get('/daejeon/bakery', 'MainController@daejeonBakery');
Route::post('/menu/check', 'MainController@menuCheck');
Route::get('/sale/event', 'MainController@saleEvent');

if (__SESSION) {
    Route::get('/logout', 'UserController@logout');
} else {
    Route::get('/login', 'UserController@login');
    Route::post('/login', 'UserController@loginProcess');
}
