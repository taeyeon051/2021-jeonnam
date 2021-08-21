<?php

use Kty\App\Route;

Route::get('/', 'MainController@index');
Route::get('/daejeon_bakery', 'MainController@daejeonBakery');

if (__SESSION) {
    Route::get('/logout', 'UserController@logout');
} else {
    Route::get('/login', 'UserController@login');
    Route::post('/login', 'UserController@loginProcess');
}
