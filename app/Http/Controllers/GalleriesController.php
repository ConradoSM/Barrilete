<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\Http\Requests\galleryRequest;
use barrilete\Http\Requests\galleryPhotosRequest;
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

        if ($gallery) {

            $photos  = $gallery->photos;

            return view('gallery', compact('gallery', 'photos'));
            
        } else return view('errors.article-error');
    }

    //PREVIEW GALERÍA
    public function previewGallery(Request $request, $id) {
        
        if ($request->ajax()) {

            $gallery = Gallery::find($id);

            if ($gallery) {

                $photos  = $gallery->photos;

                return view('auth.galleries.previewGallery', compact('gallery', 'photos'));

            } else return response()->json(['Error' => 'La galería no existe.']);
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }

    //CREAR GALERÍA
    public function createGallery(galleryRequest $request) {

        $gallery = new Gallery;
        $gallery->user_id      = $request->user_id;
        $gallery->title        = $request->title;
        $gallery->section_id   = $request->section_id;
        $gallery->author       = $request->author;
        $gallery->article_desc = $request->article_desc;
        $gallery->save();
        
        if ($gallery) { 
            
            return view('auth.galleries.formPhotosGalleries', compact('gallery'));
        
        } else return response()->json(['Error' => 'Ha ocurrido un error al cargar la galería.']);       
    }
    
    //GUARDAR FOTOS
    public function createPhotos(galleryPhotosRequest $request) {
                
        $gallery_id = $request->gallery_id;
        $titles     = $request->title;
        
        if ($request->hasFile('photo')) {    
            
            $photos = $request->photo;            
            
            foreach ($photos as $key => $val) {            
                
                //SUBIR FOTOS AL SERVIDOR
                $file        = $photos[$key];
                $filename    = date('his').'-'.$file->getClientOriginalName();           
                $upload      = public_path('img/galleries/'.$filename);
                $uploadThumb = public_path('img/galleries/.thumbs/'.$filename);

                Image::make($file->getRealPath())->save($upload);
                Image::make($file->getRealPath())->resize(700, NULL, function($constraint) {
                $constraint->aspectRatio(); })->save($uploadThumb);
                
                //GUARDAR EN BASE DE DATOS
                $GalleryPhotos             = new GalleryPhotos;
                $GalleryPhotos->gallery_id = $gallery_id;
                $GalleryPhotos->title      = $titles[$key];
                $GalleryPhotos->photo      = $filename;
                $GalleryPhotos->save();
            }
        } else return response()->json(['Error' => 'Error al leer el formato de la imagen.']);
        
        $gallery = Gallery::find($gallery_id);
        $gallery->status = 'DRAFT';
        $gallery->save();
        $photos = $gallery->photos;
        
        return view('auth.galleries.previewGallery', compact('gallery', 'photos'))
        ->with(['Exito' => 'La galería de fotos se ha creado correctamente.']);      
    }

    //BORRAR GALERÍA
    public function deleteGallery(Request $request, $id) {

        if ($request->ajax()) {

            $gallery = Gallery::find($id);
            
            if ($gallery) {
                
                $photos = $gallery->photos;
                
                if ($photos) {

                    foreach ($photos as $photo) {

                        $image_path = public_path('img/galleries/'.$photo->photo);
                        $thumb_path = public_path('img/galleries/.thumbs/'.$photo->photo);

                        if (File::exists($image_path) && File::exists($thumb_path)) {

                            File::delete($image_path);
                            File::delete($thumb_path);
                        } 
                    }

                    $gallery->delete();
                    return response()->json(['Exito' => 'La galería se ha borrado del sistema.']);
                    
                } else

                $gallery->delete();
                return response()->json(['Exito' => 'La galería se ha borrado del sistema.']);

            } else return response()->json(['Error' => 'La galería de fotos no existe.']);
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
    
    //PUBLICAR GALERÍA
    public function publishGallery(Request $request, $id) {
        
        if ($request->ajax()) {
            
            if (Auth::user()->is_admin) {
                
                $gallery         = Gallery::find($id);
                $gallery->status = 'PUBLISHED';
                $gallery->save();
                $photos = $gallery->photos;                
                
                return view('auth.galleries.previewGallery', compact('gallery','photos'))
                ->with(['Exito' => 'La galería de fotos se ha publicado correctamente.']);
                
            } else return response()->json(['Error' => 'Tu no eres administrador del sistema.']);
            
        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);       
    }
    
    //ACTUALIZAR GALERIA
    public function updateGallery(galleryRequest $request) {
            
        $gallery = Gallery::find($request->id);
        
        if ($gallery) {
            
            $gallery->title        = $request->title;
            $gallery->article_desc = $request->article_desc;
            $gallery->status       = 'DRAFT';
            $gallery->save();

            return response()->json([
                
                'Exito'        => 'Se ha actuaizado correctamente.',
                'title'        => $gallery->title,
                'article_desc' => $gallery->article_desc
            ]);

        } else return response()->json(['Error' => 'La galería no existe.']);
    }
    
    //MAS FOTOS
    public function morePhotos(Request $request) {
        
        $gallery = Gallery::find($request->id);
        
        if ($gallery) {
            
            return view('auth.galleries.formPhotosGalleries', compact('gallery'));
            
        } else return response()->json(['Error' => 'La galería no existe.']);
    }

    //GALERÍAS SIN PUBLICAR
    public function unpublishedGalleries(Request $request) {

        if (Auth::user()->is_admin) {

            if (Auth::user()->is_admin) {

                $Articles = Gallery::unpublished();
                return view('auth.viewArticles', compact('Articles'))
                ->with('status','galerías');

            } else return response()->json(['Error' => 'Tu no eres administrador del sistema.']);

        } else return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }
}