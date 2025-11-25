<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'school_name' => ['required', 'string', 'max:255'],
            'school_type' => ['nullable', 'string', 'max:255'],
            'school_zipcode' => ['required', 'numeric', 'digits:5'],
            'school_city' => ['required', 'string', 'max:255'],
            'teacher' => ['required', 'string', 'max:255'],
            'teacher_email' => ['required', 'email', 'max:255'],
            'teacher_phone' => ['nullable', 'string', 'max:255'],
            'classes' => ['required', 'array', 'min:1'],
            'classes.*.name' => ['required', 'string', 'max:255'],
            'classes.*.time' => ['required', 'string', 'max:255'],
            'classes.*.students_count' => ['required', 'integer', 'min:1'],
        ];
    }
}
