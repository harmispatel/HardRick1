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

    // public function getUser()
    // {
    //     return $this->hasOne(User::class, 'id', 'user_id');
    // }

}
