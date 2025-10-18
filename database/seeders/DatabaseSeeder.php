<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\ContactPerson;
use App\Models\Degree;
use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\JobFairDate;
use App\Models\Location;
use App\Models\MailTemplate;
use App\Models\Profession;
use App\Models\SchoolRegistration;
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

        MailTemplate::factory()
            ->count(3)
            ->create();
    }
}
