<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model {

    protected $table = 'gallery';
    
    public function scopeGalleryHome($query) {
        
        return $query->select('gallery.id', 'gallery.title', 'gallery_photos.photo')
        ->join('gallery_photos','gallery.id','gallery_photos.gallery_id')
        ->where('gallery.status','PUBLISHED')
        ->groupBy('gallery.id')
        ->orderBy('gallery.id','DESC');      
    }

    public function user() {

        return $this->belongsTo(User::class);
    }

    public function section() {

        return $this->belongsTo(Sections::class);
    }

    public function galleryPhotos() {

        return $this->hasMany(GalleryPhotos::class);
    }

    public function scopeSearch($query, $busqueda) {

        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }
}