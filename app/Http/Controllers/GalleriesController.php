<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use barrilete\Gallery;
use barrilete\GalleryPhotos;
use barrilete\Sections;
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
    public function createGallery(Request $request) {

        $article = new Gallery;
        $article -> user_id = $request['user_id'];
        $article -> title = $request['title'];
        $article -> date = $request['date'];
        $article -> section_id = $request['section_id'];
        $article -> author = $request['author'];
        $article -> article_desc = $request['article_desc'];
        $article -> save();                   
        $gallery = Gallery::find($article->id);

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

                return view('auth.galleries.deleteGalleryStatus')
                ->with('status','success');

            } else 

                return view('auth.galleries.deleteGalleryStatus')
                ->with('status','error');
        }
    }
}