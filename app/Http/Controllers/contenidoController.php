<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\GalleryPhotos;
use barrilete\Poll;
use barrilete\PollOptions;
use barrilete\PollIp;
use barrilete\Sections;

class contenidoController extends Controller {

    /*     * TITULARES INDEX* */

    public function home() {

        $articlesIndex = Articles::articlesHome()->get();
        $galleryIndex = Gallery::galleryHome()->first();
        $pollsIndex = Poll::pollHome()->get();

        return view('default', compact('articlesIndex','galleryIndex','pollsIndex'));
    }

    /*     * BUSCADOR DE CONTENIDO* */

    public function search(Request $request) {

        $seccion = $request->input('sec');
        $busqueda = $request->input('query');

        if ($seccion == 'articulos') {
            $resultado = Articles::search($busqueda);
        } elseif ($seccion == 'galerias') {
            $resultado = Gallery::search($busqueda);
        } elseif ($seccion == 'encuestas') {
            $resultado = Poll::search($busqueda);
        } else
            return view('errors.search-error');

        $resultado->appends(['query' => $busqueda, 'sec' => $seccion]);

        return view('search', compact('resultado'));
    }

    /*     * VER ARTÍCULO SEGÚN  ID Y SECCIÓN* */

    public function showArticle($id) {

        $article = Articles::showArticle($id);

        if ($article->exists()) {

            $article = $article->first();

            $moreArticles = Articles::moreArticles($id)->get();

            return view('article', compact('article','moreArticles'));
            
        } else
            
            return view('errors.article-error');
    }

    /*     * VER SECCIONES* */

    public function searchSection($name) {

        $section = Sections::searchSection($name);
        
        if ($section->exists()) {
            
            $section = $section->first();
            
            if ($section->name == 'galerias') {
                
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

    /*     * MOSTRAR ENCUESTA* */

    public function poll($id) {

        $poll = Poll::poll($id);
        $morePolls = Poll::morePolls($id);

        if ($poll->exists()) {

            $ip = PollIp::ip($id)->first();

            if (!$ip) {

                $poll = $poll->first();
                $poll_options = $poll->option;
                $morePolls = $morePolls->get();

                view('poll', compact('poll','poll_options','morePolls'))
                        ->with('status', false);
            } else {

                $poll = $poll->first();
                $poll_options = $poll->option;
                $totalVotes = $poll_options->sum('votes');
                $morePolls = $morePolls->get();

                return view('poll', compact('poll','poll_options','totalVotes','morePolls'))
                        ->with('status', 'Ya has votado!');
            }
        } else {
            return view('errors.article-error');
        }
    }

    /*     * VOTOS DE LA ENCUESTA* */

    public function pollVote(Request $request) {

        $idOpcion = $request->input('id_opcion');
        $poll_id = $request->input('id_encuesta');
        $pollTitle = $request->input('titulo_encuesta');
        $ip = $request->input('ip');

        $optionPoll = PollOptions::options($idOpcion);

        if ($optionPoll->exists()) {

            $optionPoll->increment('votes', 1);

            PollIp::create([
                'poll_id' => $poll_id,
                'ip' => $ip
            ]);

            return redirect('poll/' . $poll_id . '/' . $pollTitle);
            
        } else
            
            return view('errors.article-error');
    }
}
