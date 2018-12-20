<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class GalleryPhotos extends Model {

    protected $table = 'gallery_photos';

    //LAS FOTOS PERTENECEN A UNA GALERÍA
    public function gallery() {

        return $this->belongsTo(Gallery::class);
    }
}
