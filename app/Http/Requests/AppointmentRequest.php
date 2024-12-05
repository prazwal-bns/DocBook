<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AppointmentRequest extends FormRequest
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

    // public function rules(){
    //     return [
    //         'appointment_date' => 'required|date|after_or_equal:today',
    //         'start_time' => 'required|date_format:H:i',
    //         'end_time' => 'required|date_format:H:i|after:start_time',
    //         'appointment_reason' => 'nullable',
    //         'doctor_id' => [
    //             'required',
    //             'exists:doctors,id',
    //             function ($attribute, $value, $fail) {
    //                 // Fetch appointments for the same day and doctor
    //                 $appointments = DB::table('appointments')
    //                     ->where('doctor_id', $value)
    //                     ->where('appointment_date', $this->appointment_date)
    //                     ->get();

    //                 // Check for overlapping appointments
    //                 $overlappingAppointment = $appointments->contains(function ($appointment) {
    //                     return (
    //                         ($this->start_time >= $appointment->start_time && $this->start_time < $appointment->end_time) ||
    //                         ($this->end_time > $appointment->start_time && $this->end_time <= $appointment->end_time) ||
    //                         ($this->start_time <= $appointment->start_time && $this->end_time >= $appointment->end_time)
    //                     );
    //                 });

    //                 if ($overlappingAppointment) {
    //                     $schedule = $appointments->map(function ($appointment) {
    //                         return $appointment->start_time . ' - ' . $appointment->end_time;
    //                     })->implode(', ');

    //                     $fail("The selected doctor is already booked for this time slot. He's not available during: {$schedule}");
    //                 }
    //             },
    //         ],
    //     ];
    // }
    

    public function rules()
    {
        $rules = [
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'appointment_reason' => 'nullable',
        ];

        // Only require doctor_id if creating a new appointment (POST)
        if ($this->isMethod('post')) {
            $rules['doctor_id'] = [
                'required',
                'exists:doctors,id',
                function ($attribute, $value, $fail) {
                    // Fetch appointments for the same day and doctor
                    $appointments = DB::table('appointments')
                        ->where('doctor_id', $value)
                        ->where('appointment_date', $this->appointment_date)
                        ->get();

                    // Check for overlapping appointments
                    $overlappingAppointment = $appointments->contains(function ($appointment) {
                        return (
                            ($this->start_time >= $appointment->start_time && $this->start_time < $appointment->end_time) ||
                            ($this->end_time > $appointment->start_time && $this->end_time <= $appointment->end_time) ||
                            ($this->start_time <= $appointment->start_time && $this->end_time >= $appointment->end_time)
                        );
                    });

                    if ($overlappingAppointment) {
                        $schedule = $appointments->map(function ($appointment) {
                            return $appointment->start_time . ' - ' . $appointment->end_time;
                        })->implode(', ');

                        $fail("The selected doctor is already booked for this time slot. He's not available during: {$schedule}");
                    }
                },
            ];
        }

        return $rules;
    }

    
}
