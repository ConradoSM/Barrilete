<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Gallery;

class GalleriesController extends Controller {

    /*     * MOSTRAR GALERÍA SEGÚN ID* */

    public function showGallery($id) {
        
        $gallery = Gallery::gallery($id);

        if ($gallery->exists()) {

            $gallery = $gallery->first();
            $photos = $gallery->photos;

            return view('gallery', compact('gallery','photos'));
            
        } else
            
            return view('errors.article-error');
    }
}
