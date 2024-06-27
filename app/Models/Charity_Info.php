<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charity_Info extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'picture',
        'description',
        'charity_type',
        'financial_license',
        'financial_license_image',
        'ad_number',
    ];
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
