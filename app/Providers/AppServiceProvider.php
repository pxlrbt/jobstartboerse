<?php

namespace App\Providers;

use App\Models\Degree;
use App\Models\Profession;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        Model::automaticallyEagerLoadRelationships();
        Model::shouldBeStrict();

        Relation::morphMap([
            'user' => User::class,
            'degree' => Degree::class,
            'profession' => Profession::class,
        ]);

        Password::defaults(function () {
            return Password::min(8)->uncompromised();
        });

        Filament::serving($this->bootFilament(...));
    }

    public function bootFilament(): void
    {
        Table::configureUsing(function (Table $table) {
            $table->defaultDateDisplayFormat('d.m.Y');
            $table->defaultDateTimeDisplayFormat('H:i');
        });

        Section::configureUsing(function (Section $section) {
            $section
                ->compact()
                ->collapsible();
        });
    }
}
