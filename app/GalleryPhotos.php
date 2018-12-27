<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class GalleryPhotos extends Model {

    protected $table = 'gallery_photos';
    protected $fillable = [
        'gallery_id', 'title', 'photo',
    ];
}
