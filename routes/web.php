<?php

use App\Models\JobFair;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    JobFair::query()
        ->orderBy('created_at', 'desc')
        ->pluck('display_name', 'id')->dd();
});
