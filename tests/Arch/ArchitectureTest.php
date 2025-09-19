<?php

use Illuminate\Support\ServiceProvider;

arch('no debugging functions')
    ->expect('App')
    ->not->toUse(['die', 'dd', 'dump', 'var_dump', 'print_r']);

arch('no globals')
    ->expect('App')
    ->not->toUse(['$_GET', '$_POST', '$_SESSION', '$_COOKIE', 'global']);

arch('security')
    ->expect('App')
    ->not->toUse(['eval', 'exec', 'shell_exec', 'system', 'passthru']);

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
