<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Articles;
use Image;

class ArticlesController extends Controller {
    /**VER ARTÍCULO SEGÚN  ID Y SECCIÓN**/

    public function showArticle($id) {

        $article = Articles::showArticle($id);

        if ($article->exists()) {

            $article = $article->first();
            $section = $article->section_id;

            $moreArticles = Articles::moreArticles($id, $section)->get();

            return view('article', compact('article', 'moreArticles'));
        } else
            return view('errors.article-error');
    }
    
    /**CARGAR ARTÍCULO**/
    public function createArticle(Request $request) {
        
            $file = $request->file('photo');            
            $filename = time().'-'.$file->getClientOriginalName();
            $upload = public_path('img/articles/images/'.$filename); 
            $uploadThumbnail = public_path('img/articles/.thumbs/images/'.$filename);
            $image = Image::make($file->getRealPath());
            $thumbnail = Image::make($file)->resize(450, NULL, function($constraint) {
            $constraint->aspectRatio(); });
            
            $thumbnail->save($uploadThumbnail);           
            $image->save($upload);
            
            $article = new Articles;
            $article -> user_id = $request['user_id'];
            $article -> title = $request['title'];
            $article -> date = $request['date'];
            $article -> section_id = $request['section_id'];
            $article -> author = $request['author'];
            $article -> article_desc = $request['article_desc'];
            $article -> photo = $filename;
            $article -> video = $request['video'];
            $article -> article_body = $request['article_body']; 
            
            $result = $article -> save();
            
            if($result) {
                
                $article = Articles::where('title',$request['title'])->first();    
                return view('auth.articles.previewArticle', compact('article'));
            
        } else { return 'ha ocurrido un error!'; }
    }
    
    /**PREVIEW ARTÍCULO**/
    public function previewArticle($id) {
        
        $article = Articles::whereId($id);

        if ($article->exists()) {

            $article = $article->first();

            return view('auth.articles.previewArticle', compact('article'));
        } else {
        return view('auth.articles.article-preview-error'); }
    }
}