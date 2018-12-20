<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $table = 'sections';
    
    public function articles() {
        
        return $this->hasMany(Articles::class, 'section_id')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);
    }
        
    public function scopeSearchSection($query, $name) {
        
        return $query->where('section',$name);
  
    }
}