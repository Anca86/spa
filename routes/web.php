<?php

Route::get('/image', 'ProductsController@image');
Route::get('/token', 'ProductsController@token');

Route::get('/spa', 'ProductsController@spa');
Route::post('/spa', 'LoginController@isSession');

Route::get('/', 'ProductsController@index');
Route::get('/index', 'ProductsController@index')->name('index');
Route::get('/cart', 'ProductsController@cart')->name('cart');
Route::post('/cart', 'ProductsController@sendOrder')->name('cart');

Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@login')->name('login');
Route::get('/logout', 'LoginController@logout')->name('logout');

Route::get('/products', 'ProductsController@show')->name('products');
Route::post('/products', 'ProductsController@show')->name('products');
Route::get('/product/{product}', 'ProductsController@edit')->name('/product/{product');
Route::post('/product/{product}', 'ProductsController@save')->name('/product/{product');
Route::get('/product', 'ProductsController@create')->name('product');
Route::post('/product', 'ProductsController@save')->name('product');
Route::get('/products/delete/{product}', 'ProductsController@destroy')->name('/products/delete/{product}');

