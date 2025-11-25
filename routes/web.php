<?php

use App\Models\Exhibitor;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    $exhibitor = Exhibitor::first();

    dd($exhibitor->billingAddress->name, $exhibitor->address->name);
});
