<?php

use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('display_name');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('survey_job_fair', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Survey::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(JobFair::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Survey::class)->constrained()->cascadeOnDelete();
            $table->string('display_name');
            $table->string('type');
            $table->json('options')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // survey_responses, survey_entries
        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Survey::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Exhibitor::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // survey_response_values
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SurveyQuestion::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SurveySubmission::class)->constrained()->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
        Schema::dropIfExists('survey_job_fair');
    }
};
