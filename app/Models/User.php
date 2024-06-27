<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'request_be_charity',
        'request_status',
        'is_active',
        'is_charity',
        'has_recommendation',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function charity_request()
    {
        return $this->hasOne(Charity_Request::class);
    }
    public function chairites_permessions()
    {
        return $this->hasOne(chairites_permessions::class);
    }
    public function charity_info()
    {
        return $this->hasOne(Charity_Info::class, 'user_id', 'id');
    }
    public function recomendation()
    {
        return $this->hasOne(recommendation_table::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function fundraisers()
    {
        return $this->hasMany(Fundraisers::class);
    }
}
