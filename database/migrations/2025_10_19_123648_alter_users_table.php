<?php

use App\Enums\Role;
use App\Models\Exhibitor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('id', function (Blueprint $table) {
                $table
                    ->foreignIdFor(Exhibitor::class)
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();

                $table
                    ->string('role')
                    ->default(Role::Exhibitor->value);
            });
        });
    }
};
