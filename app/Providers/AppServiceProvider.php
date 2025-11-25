<?php

namespace App\Providers;

use App\Models;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
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
            'address' => Models\Address::class,
            'contact_person' => Models\ContactPerson::class,
            'degree' => Models\Degree::class,
            'exhibitor' => Models\Exhibitor::class,
            'exhibitor_registration' => Models\Pivot\ExhibitorRegistration::class,
            'job_fair' => Models\JobFair::class,
            'job_fair_date' => Models\JobFairDate::class,
            'location' => Models\Location::class,
            'lounge_participation' => Models\LoungeParticipation::class,
            'mailing' => Models\Mailing::class,
            'mail_template' => Models\MailTemplate::class,
            'profession' => Models\Profession::class,
            'school_registration' => Models\SchoolRegistration::class,
            'school_registration_class' => Models\SchoolRegistrationClass::class,
            'survey' => Models\Survey::class,
            'survey_answer' => Models\SurveyAnswer::class,
            'survey_question' => Models\SurveyQuestion::class,
            'survey_submission' => Models\SurveySubmission::class,
            'user' => Models\User::class,
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

        TextInput::configureUsing(function (TextInput $input) {
            $input->maxLength(255);
        });
    }
}
