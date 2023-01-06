<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AskDoctor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'askdoctor';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function specialist()
    {
        return $this->hasOne(Specialist::class, 'id', 'specialistId');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }
    public function doctor()
    {
        return $this->hasOne(User::class, 'id', 'assign_docId');
    }
}
