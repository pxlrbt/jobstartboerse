<?php

use App\Models\Exhibitor;
use App\Models\Mailing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mailings', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('message');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mailing_exhibitor', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Mailing::class);
            $table->foreignIdFor(Exhibitor::class);
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });
    }
};
