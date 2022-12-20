<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens as PassportHasApiTokens;

class User extends Authenticatable
{
    use PassportHasApiTokens, HasFactory, Notifiable;

    public $guard = 'users';

    public $message = [
        'login' => 'Admin User login successfully',
        'signup' => 'Admin User Signup successfully',
        'not_exist' => 'Admin User does not exist',
        'wrong_password' => 'Password Incorrect',
        'logout' => 'Logout Successfully'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];
}
