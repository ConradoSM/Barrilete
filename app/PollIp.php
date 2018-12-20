<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class PollIp extends Model {

    protected $fillable = ['poll_id', 'ip'];
    protected $table = 'poll_ip';
    
    //BUSCA Y RELACIONA LA IP CON EL ID DE LA ENCUESTA
    public function scopeIp($query, $id) {
        
        $ip = Request()->ip();
        return $query->where('poll_id',$id)->where('ip',$ip);   
    }
}