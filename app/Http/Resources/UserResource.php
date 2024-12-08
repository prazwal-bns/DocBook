<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at->toDateTimeString(),
        ];

        if ($this->role === 'patient') {
            $data['patient'] = new PatientResource($this->patient);
        } elseif ($this->role === 'doctor') {
            $data['doctor'] = new DoctorResource($this->doctor);
        }

        return $data;
    }
}
