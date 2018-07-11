<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password', 'useremail', 'is_verified' // 'name', 'email', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
}


/*
php artisan make:model Stringstore -m
modify user and stringstore tables
php artisan migrate

Todo:
clean up AuthController.php


Docs:
https://laravel.com/docs/4.2/schema

{
    "success": true,
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTUzMTMxMjUwMCwiZXhwIjoxNTMxMzE2MTAwLCJuYmYiOjE1MzEzMTI1MDAsImp0aSI6IkQ2SWhUcjNlUmtBUjc5bk4iLCJzdWIiOjEzLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.sXHo0bQ6A3DXfeRI5oOuLPP6Dr3E3shwNYenZKEkxKQ"
    }
}
Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTUzMTMxMjUwMCwiZXhwIjoxNTMxMzE2MTAwLCJuYmYiOjE1MzEzMTI1MDAsImp0aSI6IkQ2SWhUcjNlUmtBUjc5bk4iLCJzdWIiOjEzLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.sXHo0bQ6A3DXfeRI5oOuLPP6Dr3E3shwNYenZKEkxKQ

*/