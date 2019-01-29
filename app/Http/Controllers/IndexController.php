<?php
namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;

use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Poll;

class IndexController extends Controller {
   
    //TITULARES INDEX
    public function home() {

        $articlesIndex = Articles::articlesHome();
        $galleryIndex = Gallery::galleryHome();
        $pollsIndex = Poll::pollsHome();

        return view('default', compact('articlesIndex','galleryIndex','pollsIndex'));
    }
}



