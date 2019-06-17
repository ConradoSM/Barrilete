<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Poll;

/**
 * Class SearchController
 * @package barrilete\Http\Controllers
 */
class SearchController extends Controller {

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function searchAuth(Request $request) {
        
        if ($request->ajax()) {

            $seccion = $request->input('sec');
            $busqueda = $request->input('query');
            $author = $request->input('author');

            if ($seccion == 'articulos') {
                $resultado = Articles::searchAuth($busqueda, $author);
            } elseif ($seccion == 'galerias') {
                $resultado = Gallery::SearchAuth($busqueda, $author);
            } elseif ($seccion == 'encuestas') {
                $resultado = Poll::SearchAuth($busqueda, $author);
            } else
                return view('errors.search-error');

            $resultado->appends(['query' => $busqueda, 'sec' => $seccion, 'author' => $author]);

            return view('auth.search', compact('resultado'));
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
}
