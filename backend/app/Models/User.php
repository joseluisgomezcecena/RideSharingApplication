<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;3X82TGJGBJG3F27QHX5J36BR
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
    /*
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'login_code',
        'remember_token',
    ];


    public function routeNotificationForTwilio()//overriding user_phone that twilio uses for our phone columm in the db.
    {
       return $this->phone;
    }


    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
