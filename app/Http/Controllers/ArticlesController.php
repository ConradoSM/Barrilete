<?php

namespace barrilete\Http\Controllers;

use barrilete\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use barrilete\Http\Requests\articleRequest;
use barrilete\Articles;
use Image;
use File;
use Throwable;

class ArticlesController extends Controller
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
     * ArticlesController constructor.
     * @param Request $request
     * @param Articles $articles
     */
    public function __construct(
        Request $request,
        Articles $articles
    )
    {
        $this->_request = $request;
        $this->_articles = $articles;
    }

    /**
     * Show Article
     * @param $id
     * @return Factory|View
     */
    public function show($id)
    {
        $article = $this->_articles->showArticle($id);
        if ($article) {
            $moreArticles = $this->_articles->moreArticles($id, $article->section_id);
            return view('article', compact('article', 'moreArticles'));
        }
        return view('errors.404');
    }

    /**
     * Create Article
     * @param articleRequest $request
     * @return Factory|RedirectResponse|View
     */
    public function create(articleRequest $request)
    {
        $article = $this->saveArticle($article = null, $id = null);
        return view('auth.articles.previewArticle', compact('article'))
            ->with(['success' => 'El artículo se ha creado correctamente.']);
    }

    /**
     * Update Article
     * @param articleRequest $request
     * @param $id
     * @return Factory|RedirectResponse|View
     */
    public function update(articleRequest $request, $id)
    {
        $article = $this->saveArticle($this->_request, $id);
        return view('auth.articles.previewArticle', compact('article'))
            ->with(['success' => 'El artículo se ha actualizado correctamente.']);
    }

    /**
     * Delete Article
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function delete($id)
    {
        if ($this->_request->ajax()) {
            $user = Auth::user();
            $article = $this->_articles->find($id);
            if ($article) {
                $this->deleteImage($article->photo);
                $article->delete();
                $articles = $user->articles()->orderBy('id','DESC')->paginate(10);
                return response()->json([
                    'view' => view('auth.viewArticles', compact('articles'))
                        ->with('status','artículos')
                        ->with('success', 'El artículo se ha borrado.')
                        ->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'El artículo no existe'],404);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * Article Preview
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function preview($id)
    {
        if ($this->_request->ajax()) {
            $article = $this->_articles->find($id);
            if ($article) {
                return response()->json([
                    'view' => view('auth.articles.previewArticle', compact('article'))->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['error' => 'El artículo no existe.'],404);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * Publish Article
     * @param $id
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function publish($id)
    {
        if ($this->_request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $article = $this->_articles->find($id);
                if ($article) {
                    $article->status = 'PUBLISHED';
                    $article->save();
                    return response()->json([
                        'view' => view('auth.articles.previewArticle', compact('article'))
                            ->with(['success' => 'El artículo se ha publicado correctamente.'])->render()
                    ])->header('Content-Type', 'application/json');
                }
                return response()->json(['error' => 'El artículo no existe.'],404);
            }
            return response()->json(['error' => 'Tu no eres administrador del sistema.'],401);
        }
        return response()->json(['error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * Unpublished Articles
     * @return Factory|JsonResponse|View
     * @throws Throwable
     */
    public function unpublished()
    {
        if ($this->_request->ajax()) {
            if (Auth::user()->authorizeRoles([User::ADMIN_USER_ROLE])) {
                $articles = $this->_articles->unpublished();
                return response()->json([
                    'view' => view('auth.viewArticles', compact('articles'))->with('status','artículos')->render()
                ])->header('Content-Type', 'application/json');
            }
            return response()->json(['Error' => 'Tu no eres administrador del sistema.'],401);
        }
        return response()->json(['Error' => 'Ésta no es una petición Ajax!']);
    }

    /**
     * @param $article
     * @param $id
     * @return mixed
     */
    protected function saveArticle($article, $id)
    {
        $request = $this->_request;
        $article = $article ? $this->_articles->find($id) : new $this->_articles;
        $article->user_id = $request['user_id'];
        $article->title = $request['title'];
        $article->section_id = $request['section_id'];
        $article->article_desc = $request['article_desc'];
        $newPhoto = $request['photo'] ? $this->imageName() : null;
        $oldPhoto = $article->photo ? $article->photo : null;
        $this->uploadImage($newPhoto, $oldPhoto);
        $article->photo = !$newPhoto ? $article->photo : $newPhoto;
        $article->video = $request['video'] ? 1 : 0;
        $article->article_body = $request['article_body'];
        $article->status = 'DRAFT';
        $article->views = !$article ? 0 : false;
        $article->author = $request['author'];
        $article->save();
        return $article;
    }

    /**
     * @param $newPhoto
     * @param $oldPhoto
     * @return void
     */
    protected function uploadImage($newPhoto, $oldPhoto)
    {
        if ($newPhoto) {
            if ($oldPhoto) {
                $this->deleteImage($oldPhoto);
            }
            $request = $this->_request;
            $upload = public_path('img/articles/images/' . $newPhoto);
            $uploadThumbnail = public_path('img/articles/.thumbs/' . $newPhoto);
            Image::make($request->file('photo')->getRealPath())->save($upload);
            Image::make($request->file('photo')->getRealPath())->resize(570, 310,
                function ($constraint) {
                    $constraint->aspectRatio();
                })->save($uploadThumbnail);
        }
    }

    /**
     * @param $imageName
     */
    protected function deleteImage($imageName)
    {
        if ($imageName) {
            $image_path = public_path('/img/articles/images/'.$imageName);
            $image_path_thumb = public_path('/img/articles/.thumbs/'.$imageName);
            if (File::exists($image_path) && File::exists($image_path_thumb)) {
                File::delete($image_path);
                File::delete($image_path_thumb);
            }
        }
    }

    /**
     * @return bool|string
     */
    protected function imageName()
    {
        $file = $this->_request->file('photo');
        return $file
        ? date('h-i-s').'-'.str_slug($file->getClientOriginalName(),'-').'.'.$file->getClientOriginalExtension()
        : null;
    }
}
