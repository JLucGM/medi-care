<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
        $rules = Patient::$editRules;
        $rules['patient_unique_id'] = 'required|regex:/^\S*$/u|unique:patients,patient_unique_id,'.$this->route('patient')->id;
        $rules['email'] = 'required|email:filter|unique:users,email,'.$this->route('patient')->user->id;
        $rules['contact'] = 'nullable|unique:users,contact,'.$this->route('patient')->user->id;
        $rules['postal_code'] = 'nullable';
        $rules['profile'] = 'mimes:jpeg,jpg,png|max:2000';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'patient_unique_id.regex' => 'Space not allowed in unique id field',
            'profile.max' => 'Profile size should be less than 2 MB',
        ];
    }
}
