<?php

use App\Http\Controllers\Api\JobFairController;
use App\Http\Controllers\Api\SchoolRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [];
});

Route::get('/job-fairs', [JobFairController::class, 'index']);
Route::get('/job-fairs/{jobFair}', [JobFairController::class, 'show']);
Route::post('/job-fairs/{jobFair}/school-registration', [SchoolRegistrationController::class, 'store']);
