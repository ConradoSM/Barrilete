<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Articulos;
use barrilete\Galerias;
use barrilete\Fotos;
use barrilete\Encuestas;
use barrilete\Votos;
use barrilete\EncuestasIp;

class contenidoController extends Controller {
    /*     * TITULARES INDEX* */

    public function index() {
        $titularesIndex = Articulos::select('id', 'titulo', 'copete', 'foto', 'seccion', 'video')
                ->where('publicar', 1)
                ->orderBy('id', 'DESC')
                ->limit(15)
                ->get();

        $galeriasIndex = Galerias::select('momentos.id', 'momentos.titulo', 'fotos.foto')
                ->join('fotos', 'momentos.id', 'fotos.idmomento')
                ->where('momentos.publicar', 1)
                ->limit(1)
                ->groupBy('momentos.id')
                ->orderBy('momentos.id', 'DESC')
                ->first();

        $pollsIndex = Encuestas::select('id', 'titulo', 'fecha')
                ->where('publicar', 1)
                ->orderBy('id', 'DESC')
                ->limit(3)
                ->get();

        return view('home')
                        ->with(compact('titularesIndex'))
                        ->with(compact('galeriasIndex'))
                        ->with(compact('pollsIndex'));
    }

    /*     * BUSCADOR DE CONTENIDO* */

    public function search(Request $request) {
        
        $busqueda = $request->input('query');
        
        $resultado = Articulos::whereRaw("MATCH (titulo,copete,cuerpo) AGAINST (? IN BOOLEAN MODE)", array($busqueda))->paginate(10);
        $resultado->appends(['query' => $busqueda]);
        
        return view('search', compact('resultado'));
    }

    /*     * VER ARTÍCULO SEGÚN  ID Y SECCIÓN* */

    public function show($id, $seccion) {
        $article = Articulos::whereId($id)
                ->where('seccion', $seccion)
                ->where('publicar', 1)
                ->limit(1);

        if ($article->exists()) {

            $article = $article->first();
            Articulos::whereId($id)
                    ->update(['visitas' => $article->visitas + 1]);

            $moreArticles = Articulos::select('id', 'titulo', 'foto', 'seccion')
                    ->where('id', '!=', $id)
                    ->where('publicar', 1)
                    ->where('seccion', $seccion)
                    ->orderBy('id', 'DESC')
                    ->limit(8)
                    ->get();

            return view('article')
                            ->with(compact('article'))
                            ->with(compact('moreArticles'));
        } else {
            return view('article_error');
        }
    }

    /*     * VER SECCIONES* */

    public function sec($seccion) {
        $section = Articulos::select('id', 'titulo', 'copete', 'foto', 'video', 'seccion')
                ->where([['seccion', $seccion], ['publicar', 1],])
                ->orderBy('id', 'DESC')
                ->limit(15);

        if ($section->exists()) {

            $section = $section->get();
            return view('section', compact('section'));
        } else {
            return view('article_error');
        }
    }

    /*     * VER GALERÍAS DE FOTOS* */

    public function galleries() {
        $galleries = Galerias::select('momentos.id', 'momentos.titulo', 'momentos.copete', 'fotos.foto')
                ->join('fotos', 'momentos.id', 'fotos.idmomento')
                ->where('momentos.publicar', 1)
                ->limit(15)
                ->groupBy('momentos.id')
                ->orderBy('momentos.id', 'DESC');

        if ($galleries->exists()) {

            $galleries = $galleries->get();
            return view('galleries', compact('galleries'));
        } else {
            return view('article_error');
        }
    }

    /*     * MOSTRAR GALERÍA SEGÚN ID* */

    public function gallery($id) {
        $gallery = Galerias::whereId($id)
                ->where('publicar', 1)
                ->limit(1);

        if ($gallery->exists()) {

            $gallery = $gallery->first();
            Galerias::whereId($id)
                    ->update(['visitas' => $gallery->visitas + 1]);

            $fotos = Fotos::where('idmomento', $id)
                    ->get();

            return view('gallery')
                            ->with(compact('gallery'))
                            ->with(compact('fotos'));
        } else {
            return view('article_error');
        }
    }

    /*     * MOSTRAR ENCUESTA* */

    public function poll($id) {
        $poll = Encuestas::whereId($id)
                ->where('publicar', 1)
                ->limit(1);

        if ($poll->exists()) {

            $poll = $poll->first();
            Encuestas::whereId($id)
                    ->update(['visitas' => $poll->visitas + 1]);

            $options = Votos::where('id_encuesta', $id)
                    ->get();

            $morePolls = Encuestas::select('id', 'titulo', 'fecha')
                    ->where('id', '!=', $id)
                    ->where('publicar', 1)
                    ->orderBy('id', 'DESC')
                    ->limit(8)
                    ->get();

            return view('poll')
                            ->with(compact('poll'))
                            ->with(compact('options'))
                            ->with(compact('morePolls'));
        } else {
            return view('article_error');
        }
    }

    /*     * VOTOS DE LA ENCUESTA* */

    public function votar(Request $request) {

        $idOpcion = $request->input('idOpcion');
        $idPoll = $request->input('id_encuesta');
        $ip = $request->input('ip');

        $ipAddr = EncuestasIp::where('id_encuesta', $idPoll)
                ->where('ip', $ip)
                ->first();

        if (!$ipAddr) {

            $optionPoll = Votos::whereId($idOpcion)
                    ->where('id_encuesta', $idPoll)
                    ->limit(1)
                    ->first();

            if ($optionPoll->exists()) {

                Votos::whereId($optionPoll->id)
                        ->where('id_encuesta', $idPoll)
                        ->update(['votos' => $optionPoll->votos + 1]);

                EncuestasIp::create($request->all());

                return 'Tu voto se ha registrado!';
                
            } else { return 'Error al seleccionar la encuesta'; }
            
        } else { return 'Tu ya votaste!'; }
        
    }
    
}