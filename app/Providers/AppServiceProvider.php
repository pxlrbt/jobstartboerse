<?php

namespace App\Providers;

use App\Models\Degree;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Model::shouldBeStrict();

        Relation::morphMap([
            'user' => User::class,
            'degree' => Degree::class,
            'profession' => Profession::class,
        ]);
    }
}
