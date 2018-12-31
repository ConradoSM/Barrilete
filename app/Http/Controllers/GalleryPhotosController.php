<?php

namespace barrilete\Http\Controllers;

use Illuminate\Http\Request;
use barrilete\GalleryPhotos;
use File;

class GalleryPhotosController extends Controller
{
    //BORRAR FOTO
    public function deletePhotoGallery($id) {

        $photo = GalleryPhotos::find($id);

        if ($photo) {

            $image_path = public_path('img/galleries/'.$photo->photo);
            
            if (File::exists($image_path)) {
                    
                File::delete($image_path);
                $photo->delete();
                
                return response()->json(['Mensaje:' => 'La foto se ha borrado del sistema.']);
                
            } else return response()->json(['Mensaje:' => 'La foto no existe.']);

            
        } else return response()->json(['Mensaje:' => 'El registro no existe en la base de datos.']);
    }
    
    //ACTUALIZAR TÍTULO FOTO
    public function updateTitlePhotoGallery(Request $request, $id) {
        
        $photo = GalleryPhotos::find($id);
        
        if ($photo) {
            
            $photo->title = $request->title;
            $photo->save();
            
            $gallery = $photo->gallery;
            $gallery->status = 'DRAFT';
            $gallery->save();
            
            return response()->json($photo->title);
            
        } else return response()->json(['Error' => 'El título no existe.']);
        
    }
}
