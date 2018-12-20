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
    
    /**TITULARES INDEX**/

    public function home() {
        
        $articlesIndex = Articles::articlesHome()->get();
        $galleryIndex = Gallery::galleryHome()->first();
        $pollsIndex = Poll::pollHome()->get();

        return view('default')
        ->with(compact('articlesIndex'))
        ->with(compact('galleryIndex'))
        ->with(compact('pollsIndex'));
    }

    /**BUSCADOR DE CONTENIDO**/

    public function search(Request $request) {

        $seccion = $request->input('sec');
        $busqueda = $request->input('query');

        if ($seccion == 'articulos') {
            $resultado = Articles::search($busqueda);
            
        } elseif ($seccion == 'galerias') {
            $resultado = Gallery::search($busqueda);
            
        } elseif ($seccion == 'encuestas') {
            $resultado = Poll::search($busqueda);
            
        } else return view('errors.search-error');

        $resultado->appends(['query' => $busqueda, 'sec' => $seccion]);

        return view('search', compact('resultado'));
    }

    /**VER ARTÍCULO SEGÚN  ID Y SECCIÓN**/

    public function showArticle($id) {

        $article = Articles::showArticle($id);

        if ($article->exists()) {

            $article = $article->first();
            
            $moreArticles = Articles::moreArticles($id)->get();

            return view('article')
            ->with(compact('article'))
            ->with(compact('moreArticles'));
            
        } else return view('errors.article-error');
    }

    /**VER SECCIONES**/

    public function searchSection($name) {
        
        $section = Sections::searchSection($name);
        
        if ($section->exists()) {
            
            $section = $section->first();
            $articles = $section->articles;

        if ($articles) {

               return view('section', compact('articles'));
            
        } else return view('errors.article-error');
        
        } else return view('errors.section-error');
    }

    /**VER GALERÍAS DE FOTOS**/

    public function gallery() {
        $galleries = Gallery::select('momentos.id', 'momentos.titulo', 'momentos.copete', 'fotos.foto')
        ->join('fotos', 'momentos.id', 'fotos.idmomento')
        ->where('momentos.publicar', 1)
        ->limit(15)
        ->groupBy('momentos.id')
        ->orderBy('momentos.id', 'DESC');

        if ($galleries->exists()) {

            $galleries = $galleries->get();
            return view('galleries', compact('galleries'));
        } else {
            return view('errors.article-error');
        }
    }

    /**MOSTRAR GALERÍA SEGÚN ID**/

    public function showgGallery($id) {
        $gallery = Gallery::whereId($id)
        ->where('publicar', 1)
        ->limit(1);

        if ($gallery->exists()) {

            $gallery = $gallery->first();
            Gallery::whereId($id)
            ->update(['visitas' => $gallery->visitas + 1]);

            $fotos = Fotos::where('idmomento', $id)
            ->get();

            return view('gallery')
            ->with(compact('gallery'))
            ->with(compact('fotos'));
        } else {
            return view('errors.article-error');
        }
    }

    /**MOSTRAR ENCUESTA**/

    public function poll($id) {
        
        $poll = Poll::poll($id);        
        
        $morePolls = Poll::morePolls($id);

        if ($poll->exists()) {

            $ip = PollIp::ip($id)->first();

            if (!$ip) {

                $poll = $poll->first();
                
                $options = Poll::searchOptions($id)->first();
                $poll_options = $options->option;
                
                $morePolls = $morePolls->get();

                return view('poll')
                ->with('status', false)
                ->with(compact('poll'))
                ->with(compact('poll_options'))
                ->with(compact('morePolls'));
            } else {
                
                $poll = $poll->first();
                
                $options = Poll::searchOptions($id)->first();
                $poll_options = $options->option;
                
                $totalVotos = $poll_options->sum('votes');
                $morePolls = $morePolls->get();
                
                return view('poll')
                ->with('status', 'Ya has votado!')
                ->with(compact('poll'))
                ->with(compact('poll_options'))
                ->with(compact('totalVotos'))
                ->with(compact('morePolls'));
            }
        } else {
            return view('errors.article-error');
        }
    }

    /**VOTOS DE LA ENCUESTA**/

    public function pollVote(Request $request) {

        $idOpcion = $request->input('id_opcion');
        $idPoll = $request->input('id_encuesta');
        $pollTitle = $request->input('titulo_encuesta');
        $ip = $request->input('ip');

        $optionPoll = PollOptions::whereId($idOpcion)
        ->where('id_encuesta', $idPoll)
        ->limit(1)
        ->first();

        if ($optionPoll->exists()) {

            PollOptions::whereId($optionPoll->id)
            ->where('id_encuesta', $idPoll)
            ->update(['votos' => $optionPoll->votos + 1]);

            PollIp::create($request->all());

            return redirect('poll/'.$idPoll.'/'.$pollTitle);
            } else {
                return view('errors.article-error');
        }       
    }
}