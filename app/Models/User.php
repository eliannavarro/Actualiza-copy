<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Storage;

class User extends Authenticatable
{
    use HasFactory;

    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'firma_path',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function data()
    {
        return $this->hasMany(Data::class, 'id_user');
    }

        /**
     * Get the URL of the signature image.
     *
     * @return string|null
     */
    public function getFirmaUrlAttribute()
    {
        return $this->firma_path ? Storage::url($this->firma_path) : null;
    }
}
