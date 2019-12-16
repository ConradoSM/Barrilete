<?php

namespace barrilete\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use barrilete\GalleryPhotos;
use Illuminate\View\View;
use Image;
use File;

class GalleryPhotosController extends Controller
{
    /**
     * BORRAR FOTO
     * @param $photoId
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deletePhoto($photoId)
    {
        $photo = GalleryPhotos::find($photoId);
        if ($photo) {
            $image_path = public_path('img/galleries/'.$photo->photo);
            $image_path_thumb = public_path('img/galleries/.thumbs/'.$photo->photo);
            if (File::exists($image_path) && File::exists($image_path_thumb)) {
                File::delete($image_path);
                File::delete($image_path_thumb);
            }
            $photo->delete();
            $gallery = $photo->gallery;
            $gallery->status = 'DRAFT';
            $gallery->save();
            $photos = $gallery->photos->first() ? $gallery->photos : [];
            return response()->json([
                'view' => view('auth.galleries.formGalleryUpdate', compact('gallery','photos'))
                    ->with(['success' => 'La foto se ha borrado del sistema.'])->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'La imagen no existe.'],404);
    }

    /**
     * ACTUALIZAR TÍTULO FOTO
     * @param Request $request
     * @return JsonResponse|Factory|View
     */
    public function updateTitlePhotoGallery(Request $request)
    {
        $photo = GalleryPhotos::find($request->id);
        if ($photo) {
            $photo->title = $request->title;
            $photo->save();
            $gallery = $photo->gallery;
            $gallery->status = 'DRAFT';
            $gallery->save();
            $photos = $gallery->photos->first() ? $gallery->photos : [];
            return view('auth.galleries.formGalleryUpdate', compact('gallery','photos'))
                ->with(['success' => 'El título se ha actualizado correctamente.']);
        }
        return response()->json(['error' => 'El título no existe.'], 404);
    }

    /**
     * ACTUALIZAR FOTO
     * @param Request $request
     * @return Factory|View|JsonResponse
     */
    public function updatePhoto(Request $request)
    {
        $photo = GalleryPhotos::find($request->id);
        if ($photo) {
            /** FOTO ACTUAL */
            $actualPhoto = public_path('img/galleries/'.$request->actual_photo);
            $actualThumb = public_path('img/galleries/.thumbs/'.$request->actual_photo);
            /** BORRAR FOTO ACTUAL */
            if (File::exists($actualPhoto) && File::exists($actualThumb)) {
                File::delete($actualPhoto);
                File::delete($actualThumb);
            }
            /** FOTO NUEVA */
            $file = $request->file('photo');
            if ($file) {
                $fileName = date('h-i-s').'-'.str_slug($file->getClientOriginalName(),'-').'.'.$file->getClientOriginalExtension();
                $upload = public_path('img/galleries/'.$fileName);
                $uploadThumb = public_path('img/galleries/.thumbs/'.$fileName);
                /** SUBIR FOTO NUEVA */
                Image::make($request->file('photo')->getRealPath())->save($upload);
                Image::make($request->file('photo')->getRealPath())->resize(570, 310,
                    function($constraint) {
                        $constraint->aspectRatio();
                    })->save($uploadThumb);
                /** GUARDAR TITULO FOTO NUEVA */
                $photo->photo = $fileName;
                $photo->save();
                $gallery = $photo->gallery;
                $gallery->status = 'DRAFT';
                $gallery->save();
                $photos = $gallery->photos->first() ? $gallery->photos : [];
                return view('auth.galleries.formGalleryUpdate', compact('gallery','photos'))
                    ->with(['success' => 'La foto se ha actualizado correctamente.']);
            }
            return response()->json(['error' => 'No se pudo procesar el requerimiento.'],500);
        }
        return response()->json(['error' => 'La imagen no existe en la base de datos.'],404);
    }
}
