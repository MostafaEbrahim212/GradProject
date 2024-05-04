<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chairty_Info extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'picture',
        'description',
        'chairty_type',
        'financial_license',
        'ad_number',
    ];
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
