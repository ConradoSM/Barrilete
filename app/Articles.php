<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $table = 'articles';
    
    public function user() {
        
        return $this->belongsTo(User::class);
    }
    public function section() {
        
        return $this->belongsTo(Sections::class);
        
    }
}
