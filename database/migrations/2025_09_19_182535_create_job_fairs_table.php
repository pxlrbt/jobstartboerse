<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_fairs', function (Blueprint $table) {
            $table->id();

            $table->string('display_name')->nullable();

            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('are_exhibitors_public')->default(false);

            $table->text('attachments')->nullable();

            $table->string('floor_plan_link')->nullable();
            $table->string('floor_plan_file')->nullable();

            $table->dateTime('lounge_registration_ends_at')->nullable();
            $table->text('lounge_files_students')->nullable();
            $table->text('lounge_files_exhibitors')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('street');
            $table->string('zipcode');
            $table->string('city');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('job_fair_location', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_fair_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('job_fair_dates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_fair_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
