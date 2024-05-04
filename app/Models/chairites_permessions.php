<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chairites_permessions extends Model
{
    protected $fillable = [
        'user_id',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
