<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;

JsonApi::register('v1')->routes(function ($api) {
    $api->resource('articles')->relationships(function ($api) {
        $api->hasOne('authors')->except('replace');
        $api->hasOne('categories')->except('replace');
    });

    $api->resource('authors')->only('index', 'read')->relationships(function ($api) {
        $api->hasMany('articles')->except('replace', 'add', 'remove');
    });

    $api->resource('categories')->relationships(function ($api) {
        $api->hasMany('articles')->except('replace', 'add', 'remove');
    });
});
