<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\GalleryPhotos;
use barrilete\Poll;
use barrilete\PollOptions;
use barrilete\PollIp;

class contenidoController extends Controller {
    
    /**TITULARES INDEX**/

    public function home() {
        
        $articlesIndex = Articles::select('id','title','article_desc','photo','section_id','video')
        ->with('section')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15)
        ->get();

        $galleryIndex = Gallery::select('gallery.id', 'gallery.title', 'gallery_photos.photo')
        ->join('gallery_photos','gallery.id','gallery_photos.gallery_id')
        ->where('gallery.status','PUBLISHED')
        ->groupBy('gallery.id')
        ->orderBy('gallery.id','DESC')
        ->first();

        $pollsIndex = Poll::select('id', 'title', 'date')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(3)
        ->get();

        return view('default')
        ->with(compact('articlesIndex'))
        ->with(compact('galleryIndex'))
        ->with(compact('pollsIndex'));
    }

    /**BUSCADOR DE CONTENIDO**/

    public function search(Request $request) {

        $busqueda = $request->input('query');
        $seccion = $request->input('sec');

        if ($seccion == 'articulos') {
            $resultado = Articles::whereRaw("MATCH (title,article_desc,article_body) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
            ->orderBy('id', 'DESC')
            ->paginate(10);
        } elseif ($seccion == 'galerias') {
            $resultado = Gallery::whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
            ->orderBy('id', 'DESC')
            ->paginate(10);
        } elseif ($seccion == 'encuestas') {
            $resultado = Poll::whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
            ->orderBy('id', 'DESC')
            ->paginate(10);
        } else {
            return view('errors.search-error');
        }

        $resultado->appends(['query' => $busqueda, 'sec' => $seccion]);

        return view('search', compact('resultado'));
    }

    /**VER ARTÍCULO SEGÚN  ID Y SECCIÓN**/

    public function showArticle($id, $seccion) {

        $article = Articles::whereId($id)
        ->where('status','PUBLISHED')
        ->with('user')
        ->with('section');

        if ($article->exists()) {

            $article = $article->first();
            Articles::whereId($id)
            ->update(['views' => $article->views + 1]);

            $moreArticles = Articles::select('id', 'title', 'photo')
            ->where('id', '!=', $id)
            ->where('status', 'PUBLISHED')
            ->with('section')
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->get();

            return view('article')
            ->with(compact('article'))
            ->with(compact('moreArticles'));
        } else {
            return view('errors.article-error');
        }
    }

    /**VER SECCIONES**/

    public function section($seccion) {
        $section = Articles::select('id', 'titulo', 'copete', 'foto', 'video', 'seccion')
        ->where([['seccion', $seccion], ['publicar', 1],])
        ->orderBy('id', 'DESC')
        ->limit(15);

        if ($section->exists()) {

            $section = $section->get();
            return view('section', compact('section'));
        } else {
            return view('errors.article-error');
        }
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
        
        $poll = Poll::whereId($id)
        ->where('publicar', 1)
        ->limit(1); 
        
        $options = PollOptions::where('id_encuesta', $id);
        
        $morePolls = Poll::select('id', 'titulo', 'fecha')
        ->where('id', '!=', $id)
        ->where('publicar', 1)
        ->orderBy('id', 'DESC')
        ->limit(4);

        if ($poll->exists()) {

            $ip = Request()->ip();
            $ipAddr = PollIp::where('id_encuesta', $id)
            ->where('ip', $ip)
            ->first();

            if (!$ipAddr) {

                $poll = $poll->first();
                $options = $options->get();
                $morePolls = $morePolls->get();
                Poll::whereId($id)->update(['visitas' => $poll->visitas + 1]);

                return view('poll')
                ->with('status', false)
                ->with(compact('poll'))
                ->with(compact('options'))
                ->with(compact('morePolls'));
            } else {
                
                $poll = $poll->first();
                $options = $options->get();
                $totalPollOptions = $options->sum('votos');
                $morePolls = $morePolls->get();
                
                return view('poll')
                ->with('status', 'Ya has votado!')
                ->with(compact('poll'))
                ->with(compact('options'))
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