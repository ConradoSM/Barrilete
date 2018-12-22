<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Poll;

class SearchController extends Controller {

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
}
