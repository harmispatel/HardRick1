<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function hasManyFoodallergy()
    {
        return $this->hasMany('App\Models\UserAllergy', 'user_id', 'id')->where('allergy_type','1');
    }

    public function hasManyDragsallergy()
    {
        return $this->hasMany('App\Models\UserAllergy', 'user_id', 'id')->where('allergy_type','2');
    }

    public function hasManyChronicDiseases()
    {
        return $this->hasMany('App\Models\UserAllergy', 'user_id', 'id')->where('allergy_type','3');
    }
    public function hasManyallergy()
    {
        return $this->hasMany(UserAllergy::class, 'user_id', 'id');
    }
    public function hasOnePatient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'id');
    }
     public function hasOneDoctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'id');
    }
    public function hasManyDoctorSpecialist()
    {
        return $this->hasMany(DoctorSpecialist::class, 'user_id', 'id');
    }
    public function hasManyDoctorTime()
    {
        return $this->hasMany(DoctorTime::class, 'user_id', 'id');
    }
     public function hasManyDoctorClinicImages()
    {
        return $this->hasMany('App\Models\DoctorClinicImages', 'user_id', 'id');
    }

    
}
