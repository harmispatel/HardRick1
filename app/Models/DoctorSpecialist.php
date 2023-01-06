<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSpecialist extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'doctor_specialist';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function hasOneDoctor()
        {
            return $this->hasOne(User::class, 'id', 'user_id');
        }
}
