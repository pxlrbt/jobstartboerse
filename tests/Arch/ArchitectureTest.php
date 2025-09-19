<?php

use Illuminate\Support\ServiceProvider;

arch()->preset()->php();

arch()->preset()->security();

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');

arch('middleware')
    ->expect('App\Http\Middleware')
    ->toHaveMethod('handle');

arch('service providers')
    ->expect('App\Providers')
    ->toExtend(ServiceProvider::class)
    ->toHaveMethod('register');
