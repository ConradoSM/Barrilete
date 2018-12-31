<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Http\Requests\galleryRequest;
use Illuminate\Support\Facades\Storage;
use barrilete\Gallery;
use barrilete\GalleryPhotos;
use barrilete\Sections;
use Auth;
use Image;
use File;

class GalleriesController extends Controller {

    //MOSTRAR GALERÍA SEGÚN ID
    public function showGallery($id) {

        $gallery = Gallery::gallery($id);

        if ($gallery->exists()) {

            $gallery = $gallery->first();
            $photos = $gallery->photos;

            return view('gallery', compact('gallery', 'photos'));
            
        } else 

            return view('errors.article-error');
    }

    //PREVIEW ARTÍCULO
    public function previewGallery($id) {

        $gallery = Gallery::whereId($id);

        if ($gallery->exists()) {

            $gallery = $gallery->first();
            $photos = $gallery->photos;

            return view('auth.galleries.previewGallery', compact('gallery', 'photos'));
            
        } else 

            return view('auth.articles.article-preview-error');
    }

    //CREAR GALERÍA
    public function createGallery(galleryRequest $request) {

        $gallery = new Gallery;
        $gallery -> user_id = $request['user_id'];
        $gallery -> title = $request['title'];
        $gallery -> date = $request['date'];
        $gallery -> section_id = $request['section_id'];
        $gallery -> author = $request['author'];
        $gallery -> article_desc = $request['article_desc'];
        $gallery -> save();

        return view('auth.galleries.formPhotosGalleries', compact('gallery'));
    }
    
    //GUARDAR FOTOS
    public function createPhotos(Request $request) {
                
        $gallery_id = $request['gallery_id'];
        $titles = $request->get('title');
        
        if ($request->hasFile('photo')) {    
            
            $photos = $request->file('photo');            
            
            foreach ($photos as $key => $val) {            
                
                /**SUBIR FOTOS AL SERVIDOR**/
                $file = $photos[$key];
                $filename = date('his').'-'.$file->getClientOriginalName();           
                $upload = public_path('img/galleries/'.$filename);            
                $image = Image::make($file->getRealPath());         
                $image->save($upload);
                
                /**GUARDAR EN BASE DE DATOS**/
                $GalleryPhotos = new GalleryPhotos;
                $GalleryPhotos->gallery_id = $gallery_id;
                $GalleryPhotos->title = $titles[$key];
                $GalleryPhotos->photo = $filename;
                $GalleryPhotos->save();
            }
        }
        
        $gallery = Gallery::find($gallery_id);
        $photos = $gallery->photos;
        
        return view('auth.galleries.previewGallery', compact('gallery', 'photos'));      
    }

    //BORRAR GALERÍA
    public function deleteGallery(Request $request, $id) {

        if ($request->ajax()) {

            $gallery = Gallery::find($id);
            $photos = $gallery->photos;

            foreach ($photos as $photo) {

                $image_path = public_path('img/galleries/'.$photo->photo);

                if (File::exists($image_path)) {
                    
                    File::delete($image_path);
                } 
            }

            $gallery->delete();

            if ($gallery) {

                return view('auth.galleries.galleryStatus')
                ->with('status','success_delete');

            } else 
                
                return view('auth.galleries.galleryStatus')
                ->with('status','error_find');
            
        } else return 'Error: ésta no es una petición Ajax!';
    }
    
    //PUBLICAR GALERÍA
    public function publishGallery(Request $request, $id) {
        
        if ($request->ajax()) {
            
            if (Auth::user()->is_admin) {
                
                $gallery = Gallery::find($id);
                $gallery->status = 'PUBLISHED';
                $gallery->save();
                $photos = $gallery->photos;
                
                
                return view('auth.galleries.previewGallery', compact('gallery','photos'));
                
            } else return view('auth.galleries.galleryStatus')
                ->with('status','error_publish');
            
        } else return 'Error: ésta no es una petición Ajax!';       
    }
    
    //ACTUALIZAR TITULO GALERIA
    public function updateTitleGallery(galleryRequest $request, $id) {
            
        $gallery = Gallery::find($id);
        
        if ($gallery) {
            
            $gallery->title = $request->title;
            $gallery->status = 'DRAFT';
            $gallery->save();

            return response()->json($gallery->title);

        } else return response()->json(['Error' => 'La galería no existe.']);
    }

    //ACTUALIZAR COPETE GALERIA
    public function updateArticleDescGallery(galleryRequest $request, $id) {
            
        $gallery = Gallery::find($id);
        
        if ($gallery) {
            
            $gallery->article_desc = $request->article_desc;
            $gallery->status = 'DRAFT';
            $gallery->save();

            return response()->json($gallery->article_desc);

        } else return response()->json(['Error' => 'La galería no existe.']);
    }
}