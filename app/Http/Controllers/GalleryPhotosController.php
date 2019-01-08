<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\GalleryPhotos;
use Image;
use File;

class GalleryPhotosController extends Controller
{
    //BORRAR FOTO
    public function deletePhotoGallery(Request $request) {

        $photo = GalleryPhotos::find($request->id);

        if ($photo) {

            $image_path = public_path('img/galleries/'.$photo->photo);
            
            if (File::exists($image_path)) {
                    
                File::delete($image_path);
                $photo->delete();               
                return response()->json(['Exito' => 'La foto se ha borrado del sistema.']);

            } else 
                $photo->delete();
                return response()->json(['Exito' => 'Se borró el registro, pero la foto no existe.']);

        } else return response()->json(['Error' => 'El registro no existe en la base de datos.']);
    }
    
    //ACTUALIZAR TÍTULO FOTO
    public function updateTitlePhotoGallery(Request $request) {
        
        $photo = GalleryPhotos::find($request->id);
        
        if ($photo) {
            
            $photo->title = $request->title;
            $photo->save();
            
            $gallery = $photo->gallery;
            $gallery->status = 'DRAFT';
            $gallery->save();
            
            return response()->json([
                'Exito' => 'El título se ha actualizado correctamente.',
                'title' => $photo->title
            ]);
            
        } else return response()->json(['Error' => 'El título no existe.']);      
    }
    
    //ACTUALIZAR FOTO
    public function updatePhoto(Request $request) { 

        $photo = GalleryPhotos::find($request->id); 

        if ($photo) {     
        
            if ($request->hasFile('photo')) {
                
                //FOTO ACTUAL
                $actualPhoto = public_path('img/galleries/'.$request->actual_photo);
                $actualThumb = public_path('img/galleries/.thumbs/'.$request->actual_photo);
                
                //FOTO NUEVA
                $filename = date('his').'-'.$request->file('photo')->getClientOriginalName();           
                $upload = public_path('img/galleries/'.$filename);
                $uploadThumb = public_path('img/galleries/.thumbs/'.$filename);
                
                if (File::exists($actualPhoto) && File::exists($actualThumb)) {
                        
                    File::delete($actualPhoto);
                    File::delete($actualThumb);

                    Image::make($request->file('photo')->getRealPath())->save($upload);         
                    Image::make($request->file('photo')->getRealPath())->resize(450, NULL, function($constraint) {
                    $constraint->aspectRatio(); })->save($uploadThumb);
                    
                    $photo->photo = $filename;
                    $photo->save();
                    
                    return response()->json([
                        'Imagen' => $photo->photo,
                        'Exito' => 'La imagen se ha actualizado con éxito.'
                    ]);
                    
                } else 

                    Image::make($request->file('photo')->getRealPath())->save($upload);         
                    Image::make($request->file('photo')->getRealPath())->resize(450, NULL, function($constraint) {
                    $constraint->aspectRatio(); })->save($uploadThumb);
                    
                    $photo->photo = $filename;
                    $photo->save();
                    
                    return response()->json([
                        'Imagen' => $photo->photo,
                        'Exito' => 'La foto actual no existe, pero se actualizó con la nueva.'
                    ]);      
                    
            } else return response()->json(['Error' => 'La foto no es un archivo válido.']);

        } else return response()->json(['Error' => 'La imagen no existe.']);
    }
}
