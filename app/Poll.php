<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model {

    protected $table = 'poll';
    
    //UNA ENCUESTA PERTENECE A UN USUARIO
    public function user() {

        return $this->belongsTo(User::class);
    }
    
    //UNA ENCUESTA PERTENECE A UNA SECCIÓN
    public function section() {

        return $this->belongsTo(Sections::class);
    }
    
    //UNA ENCUESTA TIENE MUCHAS OPCIONES
    public function option() {

        return $this->hasMany(PollOptions::class);
    }
    
    //UNA ENCUESTA TIENE MUCHAS IP
    public function ip() {

        return $this->hasMany(PollIp::class);
    }
    
    //LAS ENCUESTAS QUE SE MUESTRAN EN LA HOMEPAGE
    public function scopePollHome($query) {
        
        return $query->select('id', 'title', 'created_at')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(3);      
    }
    
    //BUSCA LA ENCUESTA POR EL ID, LA MUESTRA Y ACTUALIZA LAS VISITAS
    public function scopePoll($query, $id) {
        
        $query->whereId($id)->where('status','PUBLISHED'); 
        $query->increment('views',1);
        
        return $query;
    }

    //MUESTRA EL RESTO DE LAS ENCUESTAS
    public function scopeMorePolls($query, $id) {
        
        return $query->select('id', 'title')
        ->where('id','!=',$id)
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(8);      
    }
    
    //BÚSQUEDA DE ENCUESTAS
    public function scopeSearch($query, $busqueda) {

        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }

    //BÚSQUEDA DE ENCUESTAS USUARIOS
    public function scopeSearchAuth($query, $busqueda, $author) {
        
        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('user_id', $author)
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }
}