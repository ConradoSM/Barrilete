<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model {

    protected $table = 'gallery';
    
    //RELACIONA LA GALERÍA CON EL USUARIO QUE LA CARGÓ
    public function user() {

        return $this->belongsTo(User::class);
    }
    
    //RELACIONA LA GALERÍA CON LA SECCIÓN
    public function section() {

        return $this->belongsTo(Sections::class);
    }

    //RELACIONA LA GALERÍA CON LAS FOTOS CARGADAS
    public function photos() {

        return $this->hasMany(GalleryPhotos::class);
    }

    //BUSCA LA GALERÍA QUE SE VA A MOSTRAR EN LA HOMEPAGE
    public function scopeGalleryHome($query) {
        
        return $query->where('status','PUBLISHED');      
    }
    
    //BUSCA LA GALERÍA POR ID
    public function scopeGallery($query, $id) {
        
        $query->whereId($id)->where('status','PUBLISHED');
        $query->increment('views',1);
        
        return $query;
    }

    //BUSQUEDA DE GALERÍAS
    public function scopeSearch($query, $busqueda) {

        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }
}