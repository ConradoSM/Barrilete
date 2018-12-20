<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model {

    protected $table = 'articles';
    
    //RELACION UN ARTÍCULO A UN USUARIO
    public function user() {

        return $this->belongsTo(User::class);
    }

    //RELACIÓN UN ARTÍCULO A UNA SECCIÓN
    public function section() {

        return $this->belongsTo(Sections::class);
    }
    
    //ARTÍCULOS QUE SE VAN A MOSTRAR EN LA HOMEPAGE
    public function scopeArticlesHome($query) {
        
        return $query->select('id','title','article_desc','photo','section_id','video')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);      
    }
    
    //ARTÍCULO QUE SE VA A MOSTRAR SEGÚN EL ID
    public function scopeShowArticle($query, $id) {
        
        $query->whereId($id)->where('status','PUBLISHED'); 
        $query->increment('views',1);
        
        return $query;
    }
    
    //RESTO DE LOS ARTÍCULOS QUE SE VAN A MOSTRAR
    public function scopeMoreArticles($query, $id) {
        
        return $query->select('id', 'title', 'photo')
        ->where('id','!=',$id)
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(8);      
    }
    
    //BÚSQUEDA DE ARTÍCULOS
    public function scopeSearch($query, $busqueda) {
        
        return $query->whereRaw("MATCH (title,article_desc,article_body) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }
}