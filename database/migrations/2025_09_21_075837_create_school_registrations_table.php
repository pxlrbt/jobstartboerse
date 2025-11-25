<?php

use App\Models\JobFair;
use App\Models\SchoolRegistration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_registrations', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(JobFair::class)->constrained()->cascadeOnDelete();

            $table->string('school_name');
            $table->string('school_type')->nullable();
            $table->string('school_zipcode')->nullable();
            $table->string('school_city')->nullable();

            $table->string('teacher')->nullable();
            $table->string('teacher_email')->nullable();
            $table->string('teacher_phone')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('school_registration_classes', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(SchoolRegistration::class)->constrained()->cascadeOnDelete();

            $table->string('name')->nullable();
            $table->string('time')->nullable();
            $table->unsignedInteger('students_count')->nullable();

            $table->timestamps();
        });
    }
};
