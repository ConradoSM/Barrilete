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
     * User Articles
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function userArticles(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::query()->find($id);
            $articles = $user->articles()->orderBy('id','DESC')->paginate(10);

            return response()->json([
                'view' => view('auth.viewArticles', compact('articles'))->with('status','artículos')->render()
            ])->header('Content-Type', 'application/json');
        }

        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * User Galleries
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function userGalleries(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::query()->find($id);
            $articles = $user->gallery()->orderBy('id','DESC')->paginate(10);

            return response()->json([
                'view' => view('auth.viewArticles', compact('articles'))->with('status','galerías')->render()
            ])->header('Content-Type', 'application/json');
        }

        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * User Polls
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function userPolls(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::query()->find($id);
            $articles = $user->poll()->orderBy('id','DESC')->paginate(10);

            return response()->json([
                'view' => view('auth.viewArticles', compact('articles'))->with('status','encuestas')->render()
            ])->header('Content-Type', 'application/json');
        }

        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * Add Article Form
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function formArticle(Request $request)
    {
        if ($request->ajax()) {
            $article = null;
            $sections = Sections::query()
                ->select('id','name')
                ->where('name','!=','Encuestas')
                ->where('name','!=','Galerias')
                ->get();
            if ($request->id) {
                $article = Articles::query()->find($request->id);
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
     * Add Gallery Form
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function formGallery(Request $request)
    {
        if ($request->ajax()) {
            $section = Sections::query()->where('name','galerias')->first();
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
     * Update Gallery Form
     * @param Request $request
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function formUpdateGallery(Request $request, $id)
    {
        if ($request->ajax()) {
            $gallery = Gallery::query()->find($id);
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
     * Add Poll Form
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function formPoll(Request $request)
    {
        if ($request->ajax()) {
            $section = Sections::query()->where('name','encuestas')->first();
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
     * Log Out
     * @return RedirectResponse|Redirector
     */
    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}
