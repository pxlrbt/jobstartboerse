<?php

namespace Database\Seeders;

use App\Models\Degree;
use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\JobFairDate;
use App\Models\Location;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory(10)->create();

        Profession::factory()
            ->count(10)
            ->create();

        Degree::factory()
            ->count(10)
            ->create();

        JobFair::factory()
            ->has(Location::factory(), 'locations')
            ->has(JobFairDate::factory(), 'dates')
            ->count(5)
            ->create();

        Exhibitor::factory()
            ->count(10)
            ->create();
    }
}
