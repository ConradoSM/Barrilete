<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class EncuestasIp extends Model
{
    protected $fillable = ['id_encuesta','ip'];
    protected $table = 'encuesta_ip';
    public $timestamps = false;
}
