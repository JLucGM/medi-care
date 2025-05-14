<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return Patient::$rules;
    }

    public function messages(): array
    {
        return [
            'patient_unique_id.regex' => 'Space not allowed in unique id field',
            'profile.max' => 'Profile size should be less than 2 MB',
        ];
    }
}
