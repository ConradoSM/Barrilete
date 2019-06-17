<?php

namespace barrilete;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    //RELACIONA LOS ARTÍCULOS CARGADOS POR UN USUARIO
    public function articles() {
        
       return $this->hasMany(Articles::class);
    }
    
    //RELACIONA LAS GALERÍAS CARGADAS POR UN USUARIO
    public function gallery() {
        
       return $this->hasMany(Gallery::class);
    }
    
    //RELACIONA LAS ENCUESTAS CARGADAS POR UN USUARIO
    public function poll() {
        
       return $this->hasMany(Poll::class);
    }
}
