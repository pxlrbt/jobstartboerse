<?php

use App\Models\Address;
use App\Models\ContactPerson;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exhibitor_id')->nullable();

            $table->string('name')->nullable();
            $table->string('street');
            $table->string('zipcode');
            $table->string('city');

            $table->timestamps();
        });

        Schema::create('contact_people', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exhibitor_id')->nullable();

            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name');

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->timestamps();
        });

        Schema::create('exhibitors', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('display_name');
            $table->string('display_name_affix')->nullable();
            $table->string('website')->nullable();
            $table->unsignedSmallInteger('branch')->nullable();
            $table->string('logo_file')->nullable();

            $table
                ->foreignIdFor(Address::class, 'address_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table
                ->foreignIdFor(Address::class, 'billing_address_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table
                ->foreignIdFor(ContactPerson::class, 'contact_person_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table
                ->foreignIdFor(ContactPerson::class, 'billing_contact_person_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->text('description')->nullable();
            $table->text('internal_note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
