<?php

use App\Models\Degree;
use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\JobFairDate;
use App\Models\Location;
use App\Models\LoungeParticipation;
use App\Models\Profession;
use App\Models\SchoolRegistration;

it('returns 401 without valid api key', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    // Act
    $response = $this->getJson('/api/job-fairs');

    // Assert
    $response->assertUnauthorized();
});

it('lists only public job fairs with valid api key', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $publicJobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create(['is_public' => true]);

    $privateJobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create(['is_public' => false]);

    // Act
    $response = $this->getJson('/api/job-fairs?api_key=test-key');

    // Assert
    $response->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['id' => $publicJobFair->id])
        ->assertJsonMissing(['id' => $privateJobFair->id]);
});

it('includes dates and locations in list response', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory()->count(2), 'dates')
        ->create(['is_public' => true]);

    // Act
    $response = $this->getJson('/api/job-fairs?api_key=test-key');

    // Assert
    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'display_name',
                    'description',
                    'is_public',
                    'are_exhibitors_public',
                    'dates' => [
                        '*' => ['id', 'date', 'starts_at', 'ends_at'],
                    ],
                    'locations' => [
                        '*' => ['id', 'name', 'street', 'zipcode', 'city'],
                    ],
                    'exhibitors_count',
                ],
            ],
        ]);
});

it('includes exhibitors count in list response', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create(['is_public' => true]);

    $exhibitors = Exhibitor::factory()->count(3)->create();
    $jobFair->exhibitors()->attach($exhibitors);

    // Act
    $response = $this->getJson('/api/job-fairs?api_key=test-key');

    // Assert
    $response->assertSuccessful()
        ->assertJsonPath('data.0.exhibitors_count', 3);
});

it('shows single job fair with valid api key', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create();

    // Act
    $response = $this->getJson("/api/job-fairs/{$jobFair->id}?api_key=test-key");

    // Assert
    $response->assertSuccessful()
        ->assertJsonFragment(['id' => $jobFair->id])
        ->assertJsonStructure([
            'data' => [
                'id',
                'display_name',
                'description',
                'is_public',
                'are_exhibitors_public',
                'dates',
                'locations',
            ],
        ]);
});

it('returns 404 for non-existent job fair', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    // Act
    $response = $this->getJson('/api/job-fairs/999999?api_key=test-key');

    // Assert
    $response->assertNotFound();
});

it('includes exhibitors when requested and are_exhibitors_public is true', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create(['are_exhibitors_public' => true]);

    $exhibitor = Exhibitor::factory()->create();
    $jobFair->exhibitors()->attach($exhibitor);

    // Act
    $response = $this->getJson("/api/job-fairs/{$jobFair->id}?api_key=test-key&include=exhibitors");

    // Assert
    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'exhibitors' => [
                    '*' => ['id', 'slug', 'display_name', 'website', 'branch'],
                ],
            ],
        ])
        ->assertJsonFragment(['id' => $exhibitor->id]);
});

it('does not include exhibitors when are_exhibitors_public is false', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create(['are_exhibitors_public' => false]);

    $exhibitor = Exhibitor::factory()->create();
    $jobFair->exhibitors()->attach($exhibitor);

    // Act
    $response = $this->getJson("/api/job-fairs/{$jobFair->id}?api_key=test-key&include=exhibitors");

    // Assert
    $response->assertSuccessful()
        ->assertJsonMissingPath('data.exhibitors');
});

it('includes school registrations when requested', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->has(SchoolRegistration::factory()->count(2), 'schoolRegistrations')
        ->create();

    // Act
    $response = $this->getJson("/api/job-fairs/{$jobFair->id}?api_key=test-key&include=school_registrations");

    // Assert
    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'school_registrations' => [
                    '*' => [
                        'id',
                        'school_name',
                        'school_city',
                        'teacher',
                        'teacher_email',
                        'classes',
                        'students_count',
                    ],
                ],
            ],
        ])
        ->assertJsonCount(2, 'data.school_registrations');
});

it('includes degrees when requested', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->has(Exhibitor::factory()->has(Degree::factory()))
        ->create([
            'are_exhibitors_public' => true,
        ]);

    // Act
    $response = $this->getJson("/api/job-fairs/{$jobFair->id}?api_key=test-key&include=degrees");

    // Assert
    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'degrees' => [
                    '*' => [
                        'id',
                        'slug',
                        'display_name',
                    ],
                ],
            ],
        ])
        ->assertJsonCount(1, 'data.degrees');
});

it('includes professions when requested', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->has(Exhibitor::factory()->has(Profession::factory()))
        ->create([
            'are_exhibitors_public' => true,
        ]);

    // Act
    $response = $this->getJson("/api/job-fairs/{$jobFair->id}?api_key=test-key&include=professions");

    // Assert
    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'professions' => [
                    '*' => [
                        'id',
                        'slug',
                        'display_name',
                    ],
                ],
            ],
        ])
        ->assertJsonCount(1, 'data.professions');
});

it('includes lounge_participations when requested', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->has(LoungeParticipation::factory(), 'loungeParticipations')
        ->create([
            'are_exhibitors_public' => true,
        ]);

    // Act
    $response = $this->getJson("/api/job-fairs/{$jobFair->id}?api_key=test-key&include=lounge_participations");

    // Assert
    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'lounge_participations' => [
                    '*' => [
                        'id',
                        'model_type',
                        'model_display_name',
                        'exhibitor' => [
                            'id', 'display_name',
                        ],
                    ],
                ],
            ],
        ])
        ->assertJsonCount(1, 'data.lounge_participations');
});

it('handles multiple includes via comma-separated query param', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->has(SchoolRegistration::factory(), 'schoolRegistrations')
        ->create(['are_exhibitors_public' => true]);

    $exhibitor = Exhibitor::factory()->create();
    $jobFair->exhibitors()->attach($exhibitor);

    // Act
    $response = $this->getJson("/api/job-fairs/{$jobFair->id}?api_key=test-key&include=exhibitors,school_registrations");

    // Assert
    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'exhibitors',
                'school_registrations',
            ],
        ]);
});
