<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundraisersCategories extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'parent_id',
    ];
    use HasFactory;

    public function fundraisers()
    {
        return $this->hasMany(Fundraisers::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(FundraisersCategories::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FundraisersCategories::class, 'parent_id');
    }
}
