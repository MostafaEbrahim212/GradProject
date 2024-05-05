<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fundraisers extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'goal',
        'raised',
        'end_date',
        'account_number',
        'is_active',
        'user_id',
        'category_id',
    ];
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(FundraisersCategories::class, 'fundraisers_categories', 'fundraiser_id', 'category_id');
    }
}
