<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:filter|unique:users,email,' . $this->route('staff')->id,
            'contact' => 'nullable|unique:users,contact,' . $this->route('staff')->id,
            'password' => 'nullable|same:password_confirmation|min:6', // Cambiado a nullable
            'gender' => 'required',
            'role' => 'required',
            'profile' => 'nullable|mimes:jpeg,jpg,png|max:2000',
        ];
    }


    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'profile.max' => 'Profile size should be less than 2 MB',
        ];
    }
}
