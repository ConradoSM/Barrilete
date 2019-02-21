<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model {

    protected $table = 'gallery';
    protected $fillable = [
        'user_id', 'title', 'section_id', 'author', 'article_desc',
    ];
    
    //RELACIONA LA GALERÍA CON EL USUARIO QUE LA CARGÓ
    public function user() {

        return $this->belongsTo(User::class);
    }
    
    //RELACIONA LA GALERÍA CON LA SECCIÓN
    public function section() {

        return $this->belongsTo(Sections::class);
    }
    
    //GALERÍA DE FOTOS DE LA HOMEPAGE
    public function scopeGalleryHome($query) {
        
        return $query->where('status','PUBLISHED')
        ->latest()
        ->take(1)
        ->first();      
    }    
    
    //BUSCA LA LISTA DE GALERÍAS
    public function scopeGalleries($query) {
        
        return $query->where('status','PUBLISHED')
        ->get()
        ->sortByDesc('id');      
    }

    //RELACIONA LA GALERÍA CON LAS FOTOS CARGADAS
    public function photos() {

        return $this->hasMany(GalleryPhotos::class);
    }
    
    //BUSCA LA GALERÍA POR ID
    public function scopeGallery($query, $id) {
        
        $query->findOrFail($id)->where('status','PUBLISHED');
        $query->increment('views',1);
        
        return $query->first();
    }

    //BUSQUEDA DE GALERÍAS
    public function scopeSearch($query, $busqueda) {

        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }
    
    //BÚSQUEDA DE GALERIAS USUARIOS
    public function scopeSearchAuth($query, $busqueda, $author) {
        
        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('user_id', $author)
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }

    //GALERÍAS NO PUBLICADAS
    public function scopeUnpublished($query) {

        return $query->select('id','title','article_desc','views','status','created_at')
        ->where('status','DRAFT')
        ->orderBy('id','desc')
        ->paginate(10);      
    }
}