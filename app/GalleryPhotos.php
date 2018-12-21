<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class GalleryPhotos extends Model {

    protected $table = 'gallery_photos';

    public function photos($query) {
        
         return $this->belongsTo(Gallery::class);    
    }
}
