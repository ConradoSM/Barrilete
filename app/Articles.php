<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model {

    protected $table = 'articles';
    protected $fillable = [
        'user_id', 'title', 'section_id', 'author', 'article_desc', 'photo', 'video', 'article_body',
    ];
    
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
        
        return $query->where('status','PUBLISHED')     
        ->latest()
        ->take(24)
        ->get()
        ->sortByDesc(function($post) { return sprintf('%-12s%s',$post->section->prio, $post->created_at); });      
    }
    
    //ARTÍCULO QUE SE VA A MOSTRAR SEGÚN EL ID
    public function scopeShowArticle($query, $id) {
        
        $query->findOrFail($id)->where('status','PUBLISHED'); 
        $query->increment('views',1);
        
        return $query->first();
    }
    
    //RESTO DE LOS ARTÍCULOS QUE SE VAN A MOSTRAR
    public function scopeMoreArticles($query, $id, $section) {
        
        return $query->select('id', 'title', 'photo')
        ->where('id','!=',$id)
        ->where('section_id',$section)
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(8)
        ->get();      
    }
    
    //BÚSQUEDA DE ARTÍCULOS
    public function scopeSearch($query, $busqueda) {
        
        return $query->whereRaw("MATCH (title,article_desc,article_body) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }
    
    //BÚSQUEDA DE ARTÍCULOS USUARIOS
    public function scopeSearchAuth($query, $busqueda, $author) {
        
        return $query->whereRaw("MATCH (title,article_desc,article_body) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('user_id', $author)
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }

    //ARTÍCULOS NO PUBLICADOS
    public function scopeUnpublished($query) {

        return $query->where('status','DRAFT')
        ->orderBy('id','desc')
        ->paginate(10);      
    }
}