<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $table = 'patient';

    public $timestamps = false;

    protected $guarded = [];

    public function hasOneBloodType()
        {
            return $this->hasOne(BloodType::class, 'id', 'blood_type_id');
        }

}
