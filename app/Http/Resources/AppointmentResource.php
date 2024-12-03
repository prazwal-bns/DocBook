<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $data = [
            'id' => $this->id,
            'appointment_date' => $this->appointment_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'appointment_reason' => $this->appointment_reason
        ];

        if(Auth::user()->role === 'doctor'){
            $data['patient_name'] =  $this->patient->user->name;
        } elseif(Auth::user()->role === 'patient'){
            $data['doctor_name'] = $this->doctor->user->name;
        }

        return $data;

    }
}
