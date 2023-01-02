<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorTime extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'doctor_time';

    protected $guarded = [];
}
