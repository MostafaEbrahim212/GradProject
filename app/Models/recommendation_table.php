<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recommendation_table extends Model
{
    protected $fillable = [
        'age',
        'education_level',
        'previous_donation_type',
        'previous_volunteeer',
        'personal_interests',
        'profession',
        'user_id',
    ];
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
