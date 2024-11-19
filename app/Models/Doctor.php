<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function specialization(){
        return $this->belongsTo(Specialization::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    public function schedules(){
        return $this->hasMany(Schedule::class);
    }
}
