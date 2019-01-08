<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Sections;

class SectionsController extends Controller {
    
    /*     * VER SECCIONES* */

    public function searchSection($name) {

        $section = Sections::searchSection($name);
        
        if ($section->exists()) {
            
            $section = $section->first();
            
            if ($section->name == 'galerías') {
                
                $galleries = Gallery::galleryHome()->get();
                
                if ($galleries) {
                    
                    return view('galleries', compact('galleries'));
                    
                } else 
                    
                    return view('errors.section-error');
                
            } else

                $articles = $section->articles;

            if ($articles) {

                return view('section', compact('articles'));
                
            } else
                
                return view('errors.article-error');
            
        } else
            
                return view('errors.section-error');
    }
}
