<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model {

    protected $table = 'articles';
    
    public function scopeArticlesHome($query) {
        
        return $query->select('id','title','article_desc','photo','section_id','video')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);      
    }
    
    public function scopeShowArticle($query, $id) {
        
        $query->whereId($id)->where('status','PUBLISHED'); 
        $query->increment('views',1);
        
        return $query;
    }
    
    public function scopeMoreArticles($query, $id) {
        
        return $query->select('id', 'title', 'photo')
        ->where('id','!=',$id)
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(8);      
    }

    public function user() {

        return $this->belongsTo(User::class);
    }

    public function section() {

        return $this->belongsTo(Sections::class);
    }

    public function scopeSearch($query, $busqueda) {
        
        return $query->whereRaw("MATCH (title,article_desc,article_body) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }
}