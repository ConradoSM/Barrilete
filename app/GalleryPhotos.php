<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class GalleryPhotos extends Model {

    protected $table = 'gallery_photos';

    public function gallery() {

        return $this->belongsTo(Gallery::class);
    }
}
