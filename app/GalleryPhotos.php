<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryPhotos extends Model {

    protected $table = 'gallery_photos';
    protected $fillable = [
        'gallery_id', 'title', 'photo',
    ];


    /**
     * @return BelongsTo
     */
    public function gallery() {

        return $this->belongsTo(Gallery::class);
    }
}
