<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSchoolRegistrationRequest;
use App\Http\Resources\SchoolRegistrationResource;
use App\Models\JobFair;
use App\Models\SchoolRegistration;
use Illuminate\Http\JsonResponse;

class SchoolRegistrationController extends Controller
{
    public function store(StoreSchoolRegistrationRequest $request, JobFair $jobFair): JsonResponse
    {
        /** @var array<int, array<string, mixed>> $classesData */
        $classesData = $request->validated('classes');

        $schoolRegistration = SchoolRegistration::query()->create([
            'job_fair_id' => $jobFair->id,
            'school_name' => $request->validated('school_name'),
            'school_type' => $request->validated('school_type'),
            'school_zipcode' => $request->validated('school_zipcode'),
            'school_city' => $request->validated('school_city'),
            'teacher' => $request->validated('teacher'),
            'teacher_email' => $request->validated('teacher_email'),
            'teacher_phone' => $request->validated('teacher_phone'),
        ]);

        foreach ($classesData as $classData) {
            $schoolRegistration->classes()->create([
                'name' => $classData['name'],
                'time' => $classData['time'],
                'students_count' => $classData['students_count'],
            ]);
        }

        return new SchoolRegistrationResource($schoolRegistration->load('classes'))
            ->response()
            ->setStatusCode(201);
    }
}
