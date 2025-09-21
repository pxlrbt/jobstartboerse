<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\ContactPerson;
use App\Models\Degree;
use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\JobFairDate;
use App\Models\Location;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@jobstartboerse.de',
            'password' => Hash::make('password'),
        ]);

        User::factory()
            ->count(10)
            ->create();

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
            ->has(Address::factory(), 'address')
            ->has(Address::factory(), 'billingAddress')
            ->has(ContactPerson::factory(), 'contactPerson')
            ->has(ContactPerson::factory(), 'billingContactPerson')
            ->has(Profession::factory()->count(5))
            ->has(Degree::factory()->count(5))
            ->count(10)
            ->create();
    }
}
