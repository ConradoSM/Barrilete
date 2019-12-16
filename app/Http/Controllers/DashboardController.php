<?php

namespace barrilete\Http\Controllers;

use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use barrilete\User;
use barrilete\Sections;
use barrilete\Articles;
use barrilete\Gallery;
use Throwable;


class DashboardController extends Controller
{

    const DEFAULT_REDIRECT = '/';
    /**
     * INDEX DEL DASHBOARD
     * @return Factory|View|RedirectResponse|Redirector
     */
    public function index()
    {
        if (Auth::user()->authorizeRoles(['editor', 'admin'])) {
            return view('auth.dashboard');
        }
        return redirect(self::DEFAULT_REDIRECT);
    }

    /**
     * ARTÍCULOS DE LOS USUARIOS
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function userArticles(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::find($id);
            $articles = $user->articles()->orderBy('id','DESC')->paginate(10);
            return response()->json([
                'view' => view('auth.viewArticles', compact('articles'))->with('status','artículos')->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * GALERÍAS DE LOS USUARIOS
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function userGalleries(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::find($id);
            $articles = $user->gallery()->orderBy('id','DESC')->paginate(10);
            return response()->json([
                'view' => view('auth.viewArticles', compact('articles'))->with('status','galerías')->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * ENCUESTAS DE LOS USUARIOS
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function userPolls(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::find($id);
            $articles = $user->poll()->orderBy('id','DESC')->paginate(10);
            return response()->json([
                'view' => view('auth.viewArticles', compact('articles'))->with('status','encuestas')->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * FORMULARIO CARGAR ARTÍCULO
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function formArticle(Request $request)
    {
        if ($request->ajax()) {
            $article = null;
            $sections = Sections::select('id','name')->where('name','!=','Encuestas')->where('name','!=','Galerias')->get();
            if ($request->id) {
                $article = Articles::find($request->id);
                $sections = Sections::select('id','name')->where('name', '!=', $article->section->name)->where('name','!=','Encuestas');
            }
            if (!$sections->first()) {
                return response()->json(['error' => 'Primero debes crear alguna sección'], 500);
            }
            return response()->json([
                'view' => view('auth.articles.formArticles', compact('sections', 'article'))->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * FORMULARIO CARGAR GALERÍA
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function formGallery(Request $request)
    {
        if ($request->ajax()) {
            $section = Sections::where('name','galerias')->first();
            if (!$section) {
                return response()->json(['error' => 'Primero debes crear la sección "galerias"'], 500);
            }
            return response()->json([
                'view' => view('auth.galleries.formGalleries', compact('section'))->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * FORMULARIO ACTUALIZAR GALERÍA
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function formUpdateGallery(Request $request, $id)
    {
        if ($request->ajax()) {
            $gallery = Gallery::find($id);
            if ($gallery) {
                $photos = $gallery->photos->first() ? $gallery->photos : [];
                return response()->json([
                    'view' => view('auth.galleries.formGalleryUpdate', compact('gallery','photos'))->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'La galería no existe.'],404);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * FORMULARIO CARGAR ENCUESTA
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function formPoll(Request $request)
    {
        if ($request->ajax()) {
            $section = Sections::where('name','encuestas')->first();
            if (!$section) {
                return response()->json(['error' => 'Primero debes crear la sección "encuestas"'], 500);
            }
            return response()->json([
                'view' => view('auth.polls.formPoll', compact('section'))->render()
            ])->header('Content-Type', 'application/json');
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function logout() {
        auth()->logout();
        return redirect('/');
    }
}
