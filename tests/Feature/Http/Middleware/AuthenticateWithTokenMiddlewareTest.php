<?php

use App\Http\Middleware\AuthenticateWithTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('fails when api_key is not configured', function () {
    config()->set('jobstartboerse.api.key', null);

    $middleware = new AuthenticateWithTokenMiddleware;
    $response = new Response;
    $request = Request::create('/test', 'GET');

    $middleware->handle($request, function ($req) use ($response) {
        return $response;
    });
})->expectException(HttpException::class);

it('fails with wrong api_key', function () {
    config()->set('jobstartboerse.api.key', 'right key');

    $middleware = new AuthenticateWithTokenMiddleware;

    $response = new Response;
    $request = Request::create('/test', 'GET');
    $request->headers->set('Authorization', 'Bearer wrong key');

    $middleware->handle($request, function ($req) use ($response) {
        return $response;
    });
})->expectException(HttpException::class);

it('passed with right api_key', function () {
    config()->set('jobstartboerse.api.key', 'right key');

    $middleware = new AuthenticateWithTokenMiddleware;

    $response = new Response;
    $request = Request::create('/test', 'GET');
    $request->headers->set('Authorization', 'Bearer '.config('jobstartboerse.api.key'));

    $middleware->handle($request, function ($req) use ($response) {
        return $response;
    });
})->throwsNoExceptions();
