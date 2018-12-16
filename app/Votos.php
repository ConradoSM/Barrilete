<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Votos extends Model
{
    protected $table = 'encuesta_options';
    public $timestamps = false;
    
}
