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

        if ($article) {
            
            $moreArticles = Articles::moreArticles($id, $article->section_id);
            return view('article', compact('article', 'moreArticles'));

        } else return view('errors.article-error');
    }
    
    //CARGAR ARTÍCULO
    public function createArticle(articleRequest $request) {
                    
        $filename = time().'-'.$request->file('photo')->getClientOriginalName();
        $upload = public_path('img/articles/images/'.$filename); 
        $uploadThumbnail = public_path('img/articles/.thumbs/images/'.$filename);

        //SUBE FOTO Y MINIATURA AL SERVER
        Image::make($request->file('photo')->getRealPath())->save($upload);
        Image::make($request->file('photo')->getRealPath())->resize(450, NULL, function($constraint) {
        $constraint->aspectRatio(); })->save($uploadThumbnail);
           
        $article = new Articles;
        $article -> user_id = $request['user_id'];
        $article -> title = $request['title'];
        $article -> section_id = $request['section_id'];
        $article -> author = $request['author'];
        $article -> article_desc = $request['article_desc'];
        $article -> photo = $filename;
        $article -> video = $request['video'];
        $article -> article_body = $request['article_body']; 
        $article -> status = 'DRAFT';
        $article -> views = 0;        
        $article -> save();
        
        if ($article) {           
            return view('auth.articles.previewArticle', compact('article'))
            ->with(['Exito' => 'El artículo se ha creado correctamente.']);
        
        } else return response()->json(['Error' => 'Ha ocurrido un error al cargar el artículo.']);
    }
    
    //ACTUALIZAR ARTÍCULO
    public function updateArticle(articleRequest $request, $id) {
        
        $article = Articles::find($id);
        
        if($article) {
        
            if (empty($request->file('photo'))) {

                $article -> user_id = $request['user_id'];
                $article -> title = $request['title'];
                $article -> section_id = $request['section_id'];
                $article -> author = $request['author'];
                $article -> article_desc = $request['article_desc'];
                $article -> video = $request['video'];
                $article -> article_body = $request['article_body'];
                $article -> status = 'DRAFT';
                $article -> save(); 

                return view('auth.articles.previewArticle', compact('article'))
                ->with(['Exito' => 'El artículo se ha actualizado correctamente.']);

            } else

                $image_path = public_path('img/articles/images/'.$article->photo);
                $image_path_thumb = public_path('img/articles/.thumbs/images/'.$article->photo);

                if (File::exists($image_path) && File::exists($image_path_thumb)) {

                    File::delete($image_path);
                    File::delete($image_path_thumb);

                    $newFile = time().'-'.$request->file('photo')->getClientOriginalName();
                    $upload = public_path('img/articles/images/'.$newFile); 
                    $uploadThumbnail = public_path('img/articles/.thumbs/images/'.$newFile);

                    Image::make($request->file('photo')->getRealPath())->save($upload);
                    Image::make($request->file('photo')->getRealPath())->resize(450, NULL, function($constraint) {
                    $constraint->aspectRatio(); })->save($uploadThumbnail);           

                    $article -> user_id = $request['user_id'];
                    $article -> title = $request['title'];
                    $article -> section_id = $request['section_id'];
                    $article -> author = $request['author'];
                    $article -> article_desc = $request['article_desc'];
                    $article -> photo = $newFile;
                    $article -> video = $request['video'];
                    $article -> article_body = $request['article_body']; 
                    $article -> status = 'DRAFT';
                    $article -> save();

                    return view('auth.articles.previewArticle', compact('article'))
                    ->with(['Exito' => 'El artículo se ha actualizado correctamente.']);

                } else

                    $newFile = time().'-'.$request->file('photo')->getClientOriginalName();
                    $upload = public_path('img/articles/images/'.$newFile); 
                    $uploadThumbnail = public_path('img/articles/.thumbs/images/'.$newFile);

                    Image::make($request->file('photo')->getRealPath())->save($upload);
                    Image::make($request->file('photo')->getRealPath())->resize(450, NULL, function($constraint) {
                    $constraint->aspectRatio(); })->save($uploadThumbnail);           

                    $article -> user_id = $request['user_id'];
                    $article -> title = $request['title'];
                    $article -> section_id = $request['section_id'];
                    $article -> author = $request['author'];
                    $article -> article_desc = $request['article_desc'];
                    $article -> photo = $newFile;
                    $article -> video = $request['video'];
                    $article -> article_body = $request['article_body'];
                    $article -> status = 'DRAFT';
                    $article -> save();

                    return view('auth.articles.previewArticle', compact('article'))
                    ->with(['Exito' => 'El artículo se ha actualizado correctamente.']);       
        
                    
        } else return resource()->json(['Error' => 'El artículo no existe.']);
    }
    
    //BORRAR ARTÍCULO
    public function deleteArticle(Request $request, $id) {

        if ($request->ajax()) {

            $article = Articles::find($id);

            if ($article) {

                $image_path = public_path('img/articles/images/'.$article->photo);
                $image_path_thumb = public_path('img/articles/.thumbs/images/'.$article->photo);

                if (File::exists($image_path) && File::exists($image_path_thumb)) {

                    File::delete($image_path);
                    File::delete($image_path_thumb);
                    $article->delete();
                    
                    return response()->json(['Exito' => 'El artículo se ha borrado del sistema.']);

                } else 

                    $article->delete();                
                    return response()->json(['Exito' => 'La imagen del artículo no existe, pero el artículo igual se ha borrado del sistema.']);   

            } else return response()->json(['Error' => 'El artículo no existe.']);
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
    
    //PREVIEW ARTÍCULO
    public function previewArticle(Request $request, $id) {
        
        if ($request->ajax()) {
        
            $article = Articles::find($id);

            if ($article) {

                return view('auth.articles.previewArticle', compact('article'));

            } else return response()->json(['Error' => 'El artículo no existe.']);
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);          
    }
    
    //PUBLICAR ARTÍCULO
    public function publishArticle(Request $request, $id) {
        
        if ($request->ajax()) {
            
            if (Auth::user()->is_admin) {
                
                $article = Articles::find($id);
                $article->status = 'PUBLISHED';
                $article->save();
                
                return view('auth.articles.previewArticle', compact('article'))
                ->with(['Exito' => 'El artículo se ha publicado correctamente.']);
                
            } else return response()->json(['Error' => 'Tu no eres administrador del sistema.']);
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);       
    }

    //ARTÍCULOS SIN PUBLICAR
    public function unpublishedArticles(Request $request) {

        if ($request->ajax()) {

            if (Auth::user()->is_admin) {

                $Articles = Articles::unpublished();
                return view('auth.viewArticles', compact('Articles'))
                ->with('status','artículos');

            } else return response()->json(['Error' => 'Tu no eres administrador del sistema.']);

        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
}