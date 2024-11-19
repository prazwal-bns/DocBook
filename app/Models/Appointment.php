<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded = ['role'];
    public function doctor(){
        return $this->hasOne(Doctor::class);
    }

    public function patient(){
        return $this->hasOne(Patient::class);
    }

}
