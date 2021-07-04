<?php

namespace barrilete\Http\Controllers;

use barrilete\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use barrilete\Http\Requests\galleryRequest;
use barrilete\Http\Requests\galleryPhotosRequest;
use barrilete\Gallery;
use barrilete\GalleryPhotos;
use Image;
use File;
use Throwable;

class GalleriesController extends Controller
{
    /**
     * Show Gallery
     * @param $id
     * @return Factory|View
     */
    public function showGallery($id)
    {
        $article = Gallery::gallery($id);
        if ($article) {
            $photos  = $article->photos;

            return view('gallery', compact('article', 'photos'));
        }

        return view('errors.404');
    }

    /**
     * Gallery Preview
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function preview(Request $request, $id)
    {
        if ($request->ajax()) {
            $gallery = Gallery::query()->find($id);
            if ($gallery) {
                $photos = $gallery->photos->first() ? $gallery->photos : [];

                return response()->json([
                    'view' => view('auth.galleries.previewGallery', compact('gallery', 'photos'))->render()
                ])->header('Content-Type', 'application/json');
            }

            return response()->json(['error' => 'La galería no existe.'],404);
        }

        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * Create Gallery
     * @param galleryRequest $request
     * @return Factory|JsonResponse|View
     */
    public function createGallery(galleryRequest $request)
    {
        $gallery = new Gallery;
        $gallery->user_id = $request->user_id;
        $gallery->title = $request->title;
        $gallery->section_id = $request->section_id;
        $gallery->author = $request->author;
        $gallery->article_desc = $request->article_desc;
        $gallery->save();
        if ($gallery) {
            return view('auth.galleries.formPhotosGalleries', compact('gallery'));
        }

        return response()->json(['error' => 'Ha ocurrido un error al cargar la galería.'],500);
    }

    /**
     * Set Photos Gallery
     * @param galleryPhotosRequest $request
     * @return Factory|JsonResponse|View
     */
    public function createPhotos(galleryPhotosRequest $request)
    {
        $gallery_id = $request->gallery_id;
        $titles = $request->title;
        if ($request->hasFile('photo')) {
            $photos = $request->photo;
            foreach ($photos as $key => $val) {
                /** Upstream File */
                $file = $photos[$key];
                $filename = date('h-i-s').'-'.str_slug($file->getClientOriginalName(),'-').'.'.$file->getClientOriginalExtension();
                $upload = public_path('img/galleries/images/'.$filename);
                $uploadThumb = public_path('img/galleries/.thumbs/'.$filename);
                Image::make($file->getRealPath())->save($upload);
                Image::make($file->getRealPath())->resize(570, 310, function($constraint) {
                $constraint->aspectRatio(); })->save($uploadThumb);
                /** Save in Data Base */
                $galleryPhotos = new GalleryPhotos;
                $galleryPhotos->gallery_id = $gallery_id;
                $galleryPhotos->title = $titles[$key];
                $galleryPhotos->photo = $filename;
                $galleryPhotos->save();
            }
            $gallery = Gallery::query()->find($gallery_id);
            $gallery->status = 'DRAFT';
            $gallery->save();
            $photos = $gallery->photos;

            return view('auth.galleries.previewGallery', compact('gallery', 'photos'))
                ->with(['success' => 'La galería de fotos se ha creado correctamente.']);
        }

        return response()->json(['error' => 'Error al leer el formato de la imagen.'],500);
    }

    /**
     * Delete Gallery
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function delete(Request $request, $id) : JsonResponse
    {
        if ($request->ajax()) {
            $gallery = Gallery::query()->find($id);
            if ($gallery) {
                $photos = $gallery->photos;
                if ($photos) {
                    foreach ($photos as $item) {
                        $image_path = public_path('img/galleries/images/'.$item->photo);
                        $thumb_path = public_path('img/galleries/.thumbs/'.$item->photo);
                        if (File::exists($image_path) && File::exists($thumb_path)) {
                            File::delete($image_path);
                            File::delete($thumb_path);
                        }
                    }
                }
                $gallery->delete();
                $user = Auth::user();
                $articles = $user->articles()->orderBy('id','DESC')->paginate(10);

                return response()->json([
                    'view' => view('auth.viewArticles', compact('articles'))
                        ->with('status','galerías')
                        ->with('success', 'La galería se ha borrado.')
                        ->render()
                ])->header('Content-Type', 'application/json');
            }

            return response()->json(['error' => 'La galería de fotos no existe.'],404);
        }

        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * Publish Gallery
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function publishGallery(Request $request, $id)
    {
        if ($request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $gallery = Gallery::query()->find($id);
                $photos = $gallery->photos->first() ? $gallery->photos : [];
                if ($photos) {
                    $gallery->status = 'PUBLISHED';
                    $gallery->save();

                    return response()->json([
                        'view' => view('auth.galleries.previewGallery', compact('gallery','photos'))
                            ->with(['success' => 'La galería de fotos se ha publicado correctamente.'])->render()
                    ])->header('Content-Type', 'application/json');
                }

                return response()->json(['error' => 'La galería de fotos no se publicó, porque no hay fotos relacionadas.'],403);
            }

            return response()->json(['error' => 'Tu no eres administrador del sistema.'],401);
        }

        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * Update Gallery
     * @param galleryRequest $request
     * @return JsonResponse|Factory|View
     */
    public function update(galleryRequest $request)
    {
        $gallery = Gallery::query()->find($request->id);
        if ($gallery) {
            $gallery->title = $request->title;
            $gallery->article_desc = $request->article_desc;
            $gallery->status = 'DRAFT';
            $gallery->save();
            $photos  = $gallery->photos->first() ? $gallery->photos : [];

            return view('auth.galleries.formGalleryUpdate', compact('gallery', 'photos'))
                ->with(['success' => 'Se ha actuaizado correctamente.']);
        }

        return response()->json(['error' => 'La galería no existe.'],404);
    }

    /**
     * Add More Photos
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function morePhotos(Request $request)
    {
        $gallery = Gallery::query()->find($request->id);
        if ($gallery) {
            return response()->json([
                'view' => view('auth.galleries.formPhotosGalleries', compact('gallery'))->render()
            ])->header('Content-Type', 'application/json');
        }

        return response()->json(['error' => 'La galería no existe.'],404);
    }

    /**
     * Unpublished Galleries
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function unpublishedGalleries(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $articles = Gallery::unpublished();

                return response()->json([
                    'view' => view('auth.viewArticles', compact('articles'))->with('status','galerías')->render()
                ])->header('Content-Type', 'application/json');
            }

            return response()->json(['error' => 'Tu no eres administrador del sistema.'],401);
        }

        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }
}
