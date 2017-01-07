<?php

use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/register', 'RegisterController@register');


Route::group(['prefix' => 'topics'], function() {


  Route::get('/', 'TopicController@index');
  Route::post('/', 'TopicController@store')->middleware('auth:api');


});
