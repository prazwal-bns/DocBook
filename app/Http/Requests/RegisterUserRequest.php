<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterUserRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'max:10'],
            'password' => ['required', 'confirmed'],
            'role' => ['required', 'in:patient,doctor'],
            'specialization_id' => ['required_if:role,doctor', 'exists:specializations,id'],
        ];
    }

    public function messages()
    {
        return [
            'specialization_id.required_if' => 'The specialization field is required when registering as a doctor.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
