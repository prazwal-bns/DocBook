<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
}
