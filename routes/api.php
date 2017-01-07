<?php

use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/register', 'RegisterController@register');


Route::group(['prefix' => 'topics'], function() {

  Route::post('/', 'TopicController@store')->middleware('auth:api');
  Route::get('/', 'TopicController@index');
  Route::get('/{topic}', 'TopicController@show');
  Route::patch('/{topic}', 'TopicController@update')->middleware('auth:api');
  Route::delete('/{topic}', 'TopicController@destroy')->middleware('auth:api');

  Route::group(['prefix' => '/{topic}/posts'], function() {

    Route::post('/', 'PostController@store')->middleware('auth:api');

  });

});
