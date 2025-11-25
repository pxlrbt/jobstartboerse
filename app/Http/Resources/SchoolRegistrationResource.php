<?php

namespace App\Http\Resources;

use App\Models\SchoolRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SchoolRegistration */
class SchoolRegistrationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_fair_id' => $this->job_fair_id,
            'school_name' => $this->school_name,
            'school_type' => $this->school_type,
            'school_zipcode' => $this->school_zipcode,
            'school_city' => $this->school_city,
            'teacher' => $this->teacher,
            'teacher_email' => $this->teacher_email,
            'teacher_phone' => $this->teacher_phone,
            'classes' => $this->classes,
            'students_count' => $this->studentsCount,
            'created_at' => $this->created_at,
        ];
    }
}
