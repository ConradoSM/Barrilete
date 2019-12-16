<?php

namespace barrilete\Http\Controllers;

use barrilete\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Sections;
use Illuminate\View\View;
use Throwable;

class SectionsController extends Controller
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Articles
     */
    protected $_articles;

    /**
     * @var Gallery
     */
    protected $_gallery;

    /**
     * @var Sections
     */
    protected $_sections;

    /**
     * SectionsController constructor.
     * @param Request $request
     * @param Articles $articles
     * @param Gallery $gallery
     * @param Sections $sections
     */
    public function __construct(
        Request $request,
        Articles $articles,
        Gallery $gallery,
        Sections $sections
    )
    {
        $this->_request = $request;
        $this->_articles = $articles;
        $this->_gallery = $gallery;
        $this->_sections = $sections;
    }

    /**
     * @param $name
     * @return Factory|View
     */
    public function searchSection($name)
    {
        $section = $this->_sections->searchSection($name);
        if ($section) {
            if ($section->name == 'galerias') {
                $galleries = $section->galleries;
                return $section->galleries()->exists() ? view('galleries', compact('galleries')) : view('errors.section');
            }
            $articles = $section->articles;
            return $section->articles()->exists() ? view('section', compact('articles')) : view('errors.section');
        }
        return view('errors.404');
    }

    /**
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function index()
    {
        $request = $this->_request;
        if ($request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $sections = $this->_sections->all()->sortByDesc('id');
                return response()->json([
                    'view' => view('auth.sections.index', compact('sections'))->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'No eres administrador del sistema.'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function newSection(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                return response()->json([
                    'view' => view('auth.sections.form')->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['Error' => 'No eres administrador del sistema.'], 401);
        }
        return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * @return Factory|View|JsonResponse|RedirectResponse
     */
    public function create()
    {
        $request = $this->_request;
        if ($request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $section = new $this->_sections;
                $section->name = strtolower($request->name);
                $section->prio = $request->prio;
                $section->save();
                $sections = $this->_sections->all();
                return view('auth.sections.index', compact('sections'))->with('success', 'La sección se ha creado correctamente.');
            }
            return response()->json(['Error' => 'No eres administrador del sistema.'],401);
        }
        return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * @param $id
     * @return Factory|JsonResponse|RedirectResponse|View
     * @throws Throwable
     */
    public function edit($id)
    {
        if ($this->_request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $section = $this->_sections->find($id);
                return response()->json([
                    'view' => view('auth.sections.form', compact('section'))->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'No eres administrador del sistema.'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax.']);
    }

    /**
     * @param $id
     * @return Factory|View|JsonResponse|RedirectResponse
     */
    public function update($id)
    {
        if ($this->_request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $request = $this->_request;
                $section = $this->_sections->find($id);
                $sections = $this->_sections->all();
                $section->name = strtolower($request->name);
                $section->prio = $request->prio;
                $section->save();
                return view('auth.sections.index', compact('sections'))->with('success', 'La sección se ha actualizado.');
            }
            return response()->json(['error' => 'No eres administrador del sistema.'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax.']);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function delete($id)
    {
        if ($this->_request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $section = $this->_sections->find($id);
                $section->delete();
                $sections = $this->_sections->all();
                return response()->json([
                    'view' => view('auth.sections.index', compact('sections'))
                        ->with('success', 'La sección se ha eliminado.')
                        ->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'No eres administrador del sistema.'], 401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax.']);
    }
}
