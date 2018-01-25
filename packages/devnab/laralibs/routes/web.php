<?php


$namespace = "\DevNab\LaraLibs\App\Http\Controllers";


Route::get('/{slug}',$namespace."\PageController@slug");

Route::get('/', $namespace."\PageController@index");