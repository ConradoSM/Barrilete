<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Http\Requests\articleRequest;
use barrilete\Articles;
use Auth;
use Image;
use File;

class ArticlesController extends Controller {
    
    //VER ARTÍCULO SEGÚN  ID Y SECCIÓN
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
    
    //CARGAR ARTÍCULO
    public function createArticle(articleRequest $request) {
        
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
        $article -> status = 'DRAFT';
        $article -> views = 0;        
        $article -> save();
            
        return view('auth.articles.previewArticle', compact('article'));           
    }
    
    //ACTUALIZAR ARTÍCULO
    public function updateArticle(articleRequest $request, $id) {
        
        $article = Articles::find($id);
        $filename = $request->file('photo');
        
        if (empty($filename)) {
            
            $article -> user_id = $request['user_id'];
            $article -> title = $request['title'];
            $article -> date = $request['date'];
            $article -> section_id = $request['section_id'];
            $article -> author = $request['author'];
            $article -> article_desc = $request['article_desc'];
            $article -> video = $request['video'];
            $article -> article_body = $request['article_body'];
            $article -> status = 'DRAFT';
            $article -> save(); 
            
            return view('auth.articles.previewArticle', compact('article'));
            
        } else
            
            $image_path = public_path('img/articles/images/'.$article->photo);
            $image_path_thumb = public_path('img/articles/.thumbs/images/'.$article->photo);
            
            if (File::exists($image_path) && File::exists($image_path_thumb)) {
                
                File::delete($image_path);
                File::delete($image_path_thumb);
        
                $file = $request->file('photo');
                $newFile = time().'-'.$file->getClientOriginalName();
                $upload = public_path('img/articles/images/'.$newFile); 
                $uploadThumbnail = public_path('img/articles/.thumbs/images/'.$newFile);
                $image = Image::make($file->getRealPath());
                $thumbnail = Image::make($file)->resize(450, NULL, function($constraint) {
                $constraint->aspectRatio(); });           
                $thumbnail->save($uploadThumbnail);           
                $image->save($upload);

                $article -> user_id = $request['user_id'];
                $article -> title = $request['title'];
                $article -> date = $request['date'];
                $article -> section_id = $request['section_id'];
                $article -> author = $request['author'];
                $article -> article_desc = $request['article_desc'];
                $article -> photo = $newFile;
                $article -> video = $request['video'];
                $article -> article_body = $request['article_body']; 
                $article -> status = 'DRAFT';
                $article -> save();
                
                return view('auth.articles.previewArticle', compact('article'));
                
            } else
                
                $file = $request->file('photo');
                $newFile = time().'-'.$file->getClientOriginalName();
                $upload = public_path('img/articles/images/'.$newFile); 
                $uploadThumbnail = public_path('img/articles/.thumbs/images/'.$filename);
                $image = Image::make($file->getRealPath());
                $thumbnail = Image::make($file)->resize(450, NULL, function($constraint) {
                $constraint->aspectRatio(); });           
                $thumbnail->save($uploadThumbnail);           
                $image->save($upload);

                $article -> user_id = $request['user_id'];
                $article -> title = $request['title'];
                $article -> date = $request['date'];
                $article -> section_id = $request['section_id'];
                $article -> author = $request['author'];
                $article -> article_desc = $request['article_desc'];
                $article -> photo = $newFile;
                $article -> video = $request['video'];
                $article -> article_body = $request['article_body'];
                $article -> status = 'DRAFT';
                $article -> save();
                
                return view('auth.articles.previewArticle', compact('article'));       
    }
    
    //BORRAR ARTÍCULO
    public function deleteArticle(Request $request, $id) {

        if ($request->ajax()) {

            $article = Articles::find($id);
            $image_path = public_path('img/articles/images/'.$article->photo);
            $image_path_thumb = public_path('img/articles/.thumbs/images/'.$article->photo);

            if ($article) {

                if (File::exists($image_path) && File::exists($image_path_thumb)) {

                    File::delete($image_path);
                    File::delete($image_path_thumb);
                    $article->delete();
                    
                    return view('auth.articles.articleStatus')
                    ->with('status','success');

                } else 

                    $article->delete();
                
                    return view('auth.articles.articleStatus')
                    ->with('status','success');   

            } else 

                return view('auth.articles.articleStatus')
                ->with('status','error_find');
            
        } else return 'Error: ésta no es una petición Ajax!';
    }
    
    //PREVIEW ARTÍCULO
    public function previewArticle(Request $request, $id) {
        
        if ($request->ajax()) {
        
            $article = Articles::find($id);

            if ($article) {

                return view('auth.articles.previewArticle', compact('article'));

            } else 

                return view('auth.articles.articleStatus')
                ->with('status','error_find');
            
        } else return 'Error: ésta no es una petición Ajax!';           
    }
    
    //PUBLICAR ARTÍCULO
    public function publishArticle(Request $request, $id) {
        
        if ($request->ajax()) {
            
            if (Auth::user()->is_admin) {
                
                $article = Articles::find($id);
                $article->status = 'PUBLISHED';
                $article->save();
                
                return view('auth.articles.previewArticle', compact('article'));
                
            } else return view('auth.articles.articleStatus')
                ->with('status','error_publish');
            
        } else return 'Error: ésta no es una petición Ajax!';       
    }
}