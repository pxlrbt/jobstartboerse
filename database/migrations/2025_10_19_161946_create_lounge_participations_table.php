<?php

use App\Models\Exhibitor;
use App\Models\JobFair;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lounge_participations', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(JobFair::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Exhibitor::class)->constrained()->cascadeOnDelete();
            $table->morphs('model');
            $table->boolean('status')->default(false);

            $table->timestamps();
        });
    }
};
