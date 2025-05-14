<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserProfileRequest extends FormRequest
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
        $id = Auth::id();

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'time_zone' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.'|regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:2000',
            'contact' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'contact.required' => 'Contact number field is required.',
            'image.max' => 'Avatar size should be less than 2 MB',
        ];
    }
}
