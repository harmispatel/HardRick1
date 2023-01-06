<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAllergy extends Model
{
    use HasFactory;

    protected $table = 'user_allergy';

    public $timestamps = false;
    
    protected $guarded = [];

    public function hasOneChronicDiseases()
    {
        return $this->hasOne(Chronicdiseases::class, 'id', 'allergy_id');
    }
    public function hasOneDragsAllergy()
    {
        return $this->hasOne(Dragsallergy::class, 'id', 'allergy_id');
    }
    public function hasOneFoodAllergy()
    {
        return $this->hasOne(Foodallergy::class, 'id', 'allergy_id');
    }

}
