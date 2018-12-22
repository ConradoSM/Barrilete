<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Articles;

class ArticlesController extends Controller {

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
}
