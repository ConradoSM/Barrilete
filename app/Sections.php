<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $table = 'sections';
    
    //RELACIONA LOS ARTÍCULOS VINCULADOS A UNA SECCIÓN
    public function articles() {
        
        return $this->hasMany(Articles::class, 'section_id')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);
    }
    
    //RELACIONA LAS GALERIAS VINCULADAS A UNA SECCIÓN
    public function galleries() {
        
        return $this->hasMany(Gallery::class, 'section_id')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);
    }
    
    //BUSCA LA SECCIÓN POR SU NOMBRE
    public function scopeSearchSection($query, $name) {
        
        return $query->where('name',$name)->firstOrFail(); 
    }
}