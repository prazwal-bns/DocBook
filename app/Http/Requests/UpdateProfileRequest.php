<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        // Get the user ID to exclude from the unique email validation
        $userId = $this->user()->id;

        // Base validation rules for the common user fields
        $rules = [
            'name' => 'nulalble|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ];

        // If the user is a patient, add specific patient fields
        if ($this->user()->role === 'patient') {
            $rules['gender'] = 'nullable|string';
            $rules['dob'] = 'nullable|date';
        }

        // If the user is a doctor, add specific doctor fields
        if ($this->user()->role === 'doctor') {
            $rules['specialization_id'] = 'nullable|exists:specializations,id';
            $rules['bio'] = 'nullable|string';
            $rules['status'] = 'nullable|in:available,not_available';
        }

        return $rules;
    }
}
