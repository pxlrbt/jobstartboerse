<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Address;
use App\Models\ContactPerson;
use App\Models\Degree;
use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\JobFairDate;
use App\Models\Location;
use App\Models\Mailing;
use App\Models\MailTemplate;
use App\Models\Profession;
use App\Models\SchoolRegistration;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Past Jobfairs
        $pastJobFairs = JobFair::factory()
            ->has(Location::factory(), 'locations')
            ->has(JobFairDate::factory()->past(), 'dates')
            ->has(SchoolRegistration::factory()->count(2))
            ->count(3)
            ->create();

        // Future Jobfairs
        $futureJobFairs = JobFair::factory()
            ->has(Location::factory(), 'locations')
            ->has(JobFairDate::factory(), 'dates')
            ->has(SchoolRegistration::factory()->count(2))
            ->count(5)
            ->create();

        $jobFairs = [...$pastJobFairs, ...$futureJobFairs];

        foreach ($jobFairs as $jobFair) {
            $jobFair->refreshDisplayName();
        }

        $exhibitors = Exhibitor::factory()
            ->has(Address::factory(), 'address')
            ->has(Address::factory(), 'billingAddress')
            ->has(ContactPerson::factory(), 'contactPerson')
            ->has(ContactPerson::factory(), 'billingContactPerson')
            ->has(Profession::factory()->count(5))
            ->has(Degree::factory()->count(5))
            ->count(10)
            ->create();

        foreach ($exhibitors as $exhibitor) {
            $exhibitor->jobFairs()->saveMany($jobFairs);
        }

        User::factory()
            ->recycle($exhibitors)
            ->create([
                'email' => 'admin@jobstartboerse.de',
                'password' => Hash::make('password'),
                'role' => Role::Admin,
            ]);

        User::factory()
            ->recycle($exhibitors)
            ->count(10)
            ->create();

        Profession::factory()
            ->count(10)
            ->create();

        Degree::factory()
            ->count(10)
            ->create();

        MailTemplate::factory()
            ->count(3)
            ->create();

        $mailings = Mailing::factory()
            ->count(3)
            ->create();

        foreach ($mailings as $mailing) {
            $recipients = $exhibitors->random(5);

            foreach ($recipients as $recipient) {
                DB::table('mailing_exhibitor')->insert([
                    'mailing_id' => $mailing->id,
                    'exhibitor_id' => $recipient->id,
                    'name' => $recipient->contactPerson->full_name,
                    'email' => $recipient->contactPerson->email,
                ]);
            }
        }

        $surveys = Survey::factory()
            ->recycle($jobFairs)
            ->has(SurveyQuestion::factory()->count(10), 'questions')
            ->count(3)
            ->create();

        foreach ($surveys as $survey) {
            $survey->jobFairs()->saveMany($jobFairs);

            for ($i = 0; $i < 4; $i++) {
                $submission = SurveySubmission::factory()
                    ->recycle($exhibitors)
                    ->for($survey)
                    ->create();

                foreach ($survey->questions as $question) {
                    SurveyAnswer::factory()
                        ->for($submission, 'submission')
                        ->for($question, 'question')
                        ->create();
                }
            }
        }

        $this->seedRealisticSurvey($jobFairs, $exhibitors);
    }

    private function seedRealisticSurvey($jobFairs, $exhibitors): void
    {
        $realisticSurvey = Survey::factory()->create([
            'display_name' => 'Zufriedenheitsumfrage JobStart-Börse 2025',
        ]);

        $realisticSurvey->jobFairs()->saveMany($jobFairs);

        $textQuestion = SurveyQuestion::query()->create([
            'survey_id' => $realisticSurvey->id,
            'display_name' => 'Was hat Ihnen an der JobStart-Börse besonders gut gefallen?',
            'type' => \App\Enums\SurveyQuestionType::Text,
            'options' => null,
        ]);

        $singleChoiceQuestion = SurveyQuestion::query()->create([
            'survey_id' => $realisticSurvey->id,
            'display_name' => 'Wie haben Sie von der JobStart-Börse erfahren?',
            'type' => \App\Enums\SurveyQuestionType::SingleChoice,
            'options' => collect([
                'Social Media',
                'Website',
                'Empfehlung',
                'Zeitung/Zeitschrift',
                'Sonstiges',
            ]),
        ]);

        $multipleChoiceQuestion = SurveyQuestion::query()->create([
            'survey_id' => $realisticSurvey->id,
            'display_name' => 'Welche Bereiche waren für Sie von besonderem Interesse?',
            'type' => \App\Enums\SurveyQuestionType::MultipleChoice,
            'options' => collect([
                'IT & Technik',
                'Handwerk',
                'Gesundheitswesen',
                'Kaufmännische Berufe',
                'Soziale Berufe',
            ]),
        ]);

        $toggleQuestion = SurveyQuestion::query()->create([
            'survey_id' => $realisticSurvey->id,
            'display_name' => 'Würden Sie die JobStart-Börse weiterempfehlen?',
            'type' => \App\Enums\SurveyQuestionType::Toggle,
            'options' => null,
        ]);

        $ratingQuestion = SurveyQuestion::query()->create([
            'survey_id' => $realisticSurvey->id,
            'display_name' => 'Wie zufrieden waren Sie insgesamt mit der Veranstaltung?',
            'type' => \App\Enums\SurveyQuestionType::Rating,
            'options' => null,
        ]);

        $textResponses = [
            'Die persönlichen Gespräche mit den Ausstellern waren sehr hilfreich und informativ.',
            'Besonders gut fand ich die große Auswahl an verschiedenen Unternehmen.',
            'Die Organisation war hervorragend und das Personal sehr freundlich.',
            'Ich konnte viele wertvolle Kontakte knüpfen.',
            'Die Atmosphäre war angenehm und professionell.',
            'Gut gefallen haben mir die praxisnahen Einblicke in verschiedene Berufsfelder.',
            'Die zentrale Lage und gute Erreichbarkeit der Messe.',
            'Besonders positiv waren die Möglichkeiten zum direkten Austausch.',
            'Die vielfältigen Ausbildungs- und Karrieremöglichkeiten, die präsentiert wurden.',
            'Alles war gut organisiert und die Aussteller sehr engagiert.',
        ];

        $singleChoiceResponses = [
            'Social Media',
            'Website',
            'Empfehlung',
            'Social Media',
            'Website',
            'Empfehlung',
            'Zeitung/Zeitschrift',
            'Empfehlung',
            'Social Media',
            'Website',
        ];

        $multipleChoiceResponses = [
            ['IT & Technik', 'Kaufmännische Berufe'],
            ['Handwerk', 'IT & Technik'],
            ['Gesundheitswesen', 'Soziale Berufe'],
            ['IT & Technik'],
            ['Kaufmännische Berufe', 'IT & Technik', 'Handwerk'],
            ['Soziale Berufe', 'Gesundheitswesen'],
            ['Handwerk'],
            ['IT & Technik', 'Gesundheitswesen'],
            ['Kaufmännische Berufe'],
            ['Handwerk', 'Soziale Berufe'],
        ];

        $toggleResponses = [true, true, true, true, false, true, true, true, true, true];

        $ratingResponses = [5, 4, 5, 5, 3, 4, 5, 4, 5, 4];

        for ($i = 0; $i < 10; $i++) {
            $submission = SurveySubmission::factory()
                ->recycle($exhibitors)
                ->for($realisticSurvey)
                ->create();

            SurveyAnswer::query()->create([
                'survey_submission_id' => $submission->id,
                'survey_question_id' => $textQuestion->id,
                'content' => $textResponses[$i],
            ]);

            SurveyAnswer::query()->create([
                'survey_submission_id' => $submission->id,
                'survey_question_id' => $singleChoiceQuestion->id,
                'content' => $singleChoiceResponses[$i],
            ]);

            SurveyAnswer::query()->create([
                'survey_submission_id' => $submission->id,
                'survey_question_id' => $multipleChoiceQuestion->id,
                'content' => $multipleChoiceResponses[$i],
            ]);

            SurveyAnswer::query()->create([
                'survey_submission_id' => $submission->id,
                'survey_question_id' => $toggleQuestion->id,
                'content' => $toggleResponses[$i],
            ]);

            SurveyAnswer::query()->create([
                'survey_submission_id' => $submission->id,
                'survey_question_id' => $ratingQuestion->id,
                'content' => $ratingResponses[$i],
            ]);
        }
    }
}
