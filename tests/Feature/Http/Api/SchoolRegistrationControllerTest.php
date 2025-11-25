<?php

use App\Models\JobFair;
use App\Models\JobFairDate;
use App\Models\Location;
use App\Models\SchoolRegistration;

it('returns 401 without valid api key', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create();

    // Act
    $response = $this->postJson("/api/job-fairs/{$jobFair->id}/school-registration", []);

    // Assert
    $response->assertUnauthorized();
});

it('creates school registration with valid data', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create();

    $data = [
        'school_name' => 'Gymnasium Musterstadt',
        'school_type' => 'Gymnasium',
        'school_zipcode' => '12345',
        'school_city' => 'Musterstadt',
        'teacher' => 'Max Mustermann',
        'teacher_email' => 'max.mustermann@example.com',
        'teacher_phone' => '0123456789',
        'classes' => [
            [
                'name' => '10a',
                'time' => '09:00 - 10:30',
                'students_count' => 25,
            ],
            [
                'name' => '10b',
                'time' => '10:30 - 12:00',
                'students_count' => 22,
            ],
        ],
    ];

    // Act
    $response = $this->postJson("/api/job-fairs/{$jobFair->id}/school-registration?api_key=test-key", $data);

    // Assert
    $response->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'job_fair_id',
                'school_name',
                'school_type',
                'school_zipcode',
                'school_city',
                'teacher',
                'teacher_email',
                'teacher_phone',
                'classes',
                'students_count',
                'created_at',
            ],
        ])
        ->assertJsonFragment([
            'school_name' => 'Gymnasium Musterstadt',
            'teacher_email' => 'max.mustermann@example.com',
            'students_count' => 47,
        ]);

    expect(SchoolRegistration::query()->count())->toBe(1);

    $schoolRegistration = SchoolRegistration::query()->first();
    expect($schoolRegistration)
        ->school_name->toBe('Gymnasium Musterstadt')
        ->teacher->toBe('Max Mustermann')
        ->job_fair_id->toBe($jobFair->id)
        ->classes->toHaveCount(2);
});

it('validates school registration fields', function (array $invalidData, string $expectedField) {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $jobFair = JobFair::factory()
        ->has(Location::factory(), 'locations')
        ->has(JobFairDate::factory(), 'dates')
        ->create();

    $baseData = [
        'school_name' => 'Test School',
        'school_zipcode' => '12345',
        'school_city' => 'Musterstadt',
        'teacher' => 'Max Mustermann',
        'teacher_email' => 'max@example.com',
        'classes' => [
            ['name' => '10a', 'time' => '09:00', 'students_count' => 25],
        ],
    ];

    $data = array_merge($baseData, $invalidData);

    // Act
    $response = $this->postJson("/api/job-fairs/{$jobFair->id}/school-registration?api_key=test-key", $data);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor($expectedField);
})->with([
    'required: school_name' => [
        ['school_name' => null],
        'school_name',
    ],
    'required: school_zipcode' => [
        ['school_zipcode' => null],
        'school_zipcode',
    ],
    'digits: school_zipcode must be 5 digits' => [
        ['school_zipcode' => '123'],
        'school_zipcode',
    ],
    'numeric: school_zipcode must be numeric' => [
        ['school_zipcode' => 'abcde'],
        'school_zipcode',
    ],
    'required: school_city' => [
        ['school_city' => null],
        'school_city',
    ],
    'required: teacher' => [
        ['teacher' => null],
        'teacher',
    ],
    'required: teacher_email' => [
        ['teacher_email' => null],
        'teacher_email',
    ],
    'email: teacher_email format' => [
        ['teacher_email' => 'invalid-email'],
        'teacher_email',
    ],
    'required: classes array' => [
        ['classes' => null],
        'classes',
    ],
    'min: classes must have at least one item' => [
        ['classes' => []],
        'classes',
    ],
    'required: class name' => [
        ['classes' => [['time' => '09:00', 'students_count' => 25]]],
        'classes.0.name',
    ],
    'required: class time' => [
        ['classes' => [['name' => '10a', 'students_count' => 25]]],
        'classes.0.time',
    ],
    'required: students_count' => [
        ['classes' => [['name' => '10a', 'time' => '09:00']]],
        'classes.0.students_count',
    ],
    'min: students_count must be at least 1' => [
        ['classes' => [['name' => '10a', 'time' => '09:00', 'students_count' => 0]]],
        'classes.0.students_count',
    ],
]);

it('returns 404 when job fair does not exist', function () {
    // Arrange
    config()->set('jobstartboerse.api.key', 'test-key');

    $data = [
        'school_name' => 'Test School',
        'school_zipcode' => '12345',
        'school_city' => 'Musterstadt',
        'teacher' => 'Max Mustermann',
        'teacher_email' => 'max@example.com',
        'classes' => [
            ['name' => '10a', 'time' => '09:00', 'students_count' => 25],
        ],
    ];

    // Act
    $response = $this->postJson('/api/job-fairs/999999/school-registration?api_key=test-key', $data);

    // Assert
    $response->assertNotFound();
});
