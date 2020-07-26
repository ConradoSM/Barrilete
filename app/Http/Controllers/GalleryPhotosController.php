<?php

namespace barrilete\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use barrilete\GalleryPhotos;
use Illuminate\View\View;
use Image;
use File;
use Throwable;

class GalleryPhotosController extends Controller
{
    /**
     * Delete Photo
     * @param $photoId
     * @return JsonResponse
     * @throws Throwable
     */
    public function deletePhoto($photoId)
    {
        $photo = GalleryPhotos::query()->find($photoId);
        if ($photo) {
            $image_path = public_path('img/galleries/images/'.$photo->photo);
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
     * Update Photo Title
     * @param Request $request
     * @return JsonResponse|Factory|View
     */
    public function updateTitlePhotoGallery(Request $request)
    {
        $photo = GalleryPhotos::query()->find($request->id);
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
     * Update Photo
     * @param Request $request
     * @return Factory|View|JsonResponse
     */
    public function updatePhoto(Request $request)
    {
        $photo = GalleryPhotos::query()->find($request->id);
        if ($photo) {
            $actualPhoto = public_path('img/galleries/images/'.$request->actual_photo);
            $actualThumb = public_path('img/galleries/.thumbs/'.$request->actual_photo);
            if (File::exists($actualPhoto) && File::exists($actualThumb)) {
                File::delete($actualPhoto);
                File::delete($actualThumb);
            }
            $file = $request->file('photo');
            if ($file) {
                $fileName = date('h-i-s').'-'.str_slug($file->getClientOriginalName(),'-').'.'.$file->getClientOriginalExtension();
                $upload = public_path('img/galleries/images/'.$fileName);
                $uploadThumb = public_path('img/galleries/.thumbs/'.$fileName);
                Image::make($request->file('photo')->getRealPath())->save($upload);
                Image::make($request->file('photo')->getRealPath())->resize(570, 310,
                    function($constraint) {
                        $constraint->aspectRatio();
                    })->save($uploadThumb);
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
