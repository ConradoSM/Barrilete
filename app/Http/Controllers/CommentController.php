<?php

namespace barrilete\Http\Controllers;

use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Poll;
use barrilete\Sections;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use barrilete\Comments;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class CommentController extends Controller
{
    /**
     * Save Comment Post
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function save(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'comment' => 'required',
                'user_id' => 'required|integer',
                'article_id' => 'required|integer',
                'section_id' => 'required|integer'
            ]);
            $comment = new Comments();
            $comment->content = $request->comment;
            $comment->parent_id = $request->parent_id ?: null;
            $comment->user_id = $request->user_id;
            $comment->article_id = $request->article_id;
            $comment->section_id = $request->section_id;
            $comment->save();
            if ($request->parent_id) {
                $parentComment = Comments::find($request->parent_id);
                $fromUser = Auth::user();
                $toUser = $parentComment->user;
                (new CommentsUserReactionsController)->sendNotification($fromUser, $toUser, $parentComment->section->name, $parentComment->article_id, '1', 'reply');
            }

            return response()->json([
                'view' => $this->get($request->article_id, $request->section_id, $request->current_page)->render(),
                'count' => Comments::articles($request->article_id, $request->section_id)->count(),
                'success' => $request->parent_id ? 'Tu respuesta se ha publicado.' : 'El comentario se ha publicado.',
                'comment_id' => $comment->id
            ])->header('Content-Type', 'application/json');
        }

        return abort(500);
    }

    /**
     * Get Comments
     * @param $id
     * @param $section_id
     * @param null $page
     * @return Factory|View
     */
    public function get($id, $section_id, $page = null)
    {
        $comments = Comments::articles($id, $section_id)
            ->orderBy('id','DESC')
            ->paginate(5, ['*'], 'page', $page)
            ->onEachSide(1)
            ->setPath(route('getComments', ['article_id' => $id, 'section_id' => $section_id]));

        if ($comments->first()) {
            return view('comments.list', compact('comments'));
        }

        return view('comments.404');
    }

    /**
     * Delete Comment
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $comment = Comments::query()->find($request->id);
            if ($comment) {
                $comment->delete();

                return response()->json([
                    'view' => $this->get($request->article_id, $request->section_id)->render(),
                    'count' => Comments::articles($request->article_id, $request->section_id)->count(),
                    'success' => 'El comentario se ha borrado.',
                    'comment_id' => null
                ])->header('Content-Type', 'application/json');
            }

            return response()->json(['error' => 'El comentario no existe'],404);
        }

        return abort(404);
    }

    /**
     * Update Comment
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $comment = Comments::query()->find($request->id);
            if ($comment) {
                $comment->content = $request->comment;
                $comment->save();

                return response()->json([
                    'view' => $this->get($request->article_id, $request->section_id, $request->current_page)->render(),
                    'count' => Comments::articles($request->article_id, $request->section_id)->count(),
                    'success' => 'El comentario se ha actualizado.',
                    'comment_id' => $comment->id
                ])->header('Content-Type', 'application/json');
            }

            return response()->json(['error' => 'El comentario no existe'],404);
        }

        return abort(404);
    }

    /**
     * @param array $message
     * @return JsonResponse
     * @throws Throwable
     */
    public function getAllComments(array $message = []) : JsonResponse
    {
        $comments = Comments::query()->orderBy('created_at', 'DESC')
            ->paginate(20)->setPath(route('getAllComments'));

        return response()->json([
            'view' => view('comments.all-comments', compact('comments', 'message'))->render()
        ])->header('Content-Type', 'application/json');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCommentById(Request $request) : JsonResponse
    {
        $comment = Comments::query()->findOrFail($request->id);

        return response()->json([
            'date' => ucfirst($comment->created_at->diffForHumans()),
            'content' => $comment->content,
            'article_link' => $this->getArticleLink($comment->article_id, $comment->section_id)
        ]);
    }

    /**
     * @param $article_id
     * @param $section_id
     * @return string
     */
    protected function getArticleLink($article_id, $section_id) : string
    {
        $article = Articles::query()->where('id', $article_id)->where('section_id', $section_id)->first();
        $gallery = Gallery::query()->where('id', $article_id)->where('section_id', $section_id)->first();
        $poll = Poll::query()->where('id', $article_id)->where('section_id', $section_id)->first();

        $link = '';
        if ($article) {
            $link = route('article', [
                'id' => $article->id,
                'section' => str_slug($article->section->name),
                'title' => str_slug($article->title,'-')
            ]);
        }

        if ($gallery) {
            $link = route('gallery', [
                'id' => $gallery->id,
                'title' => str_slug($gallery->title,'-')
            ]);
        }

        if ($poll) {
            $link = route('poll', [
                'id' => $poll->id,
                'title' => str_slug($poll->title,'-')
            ]);
        }

        return $link;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function deleteCommentById(Request $request) : JsonResponse
    {
        $comment = Comments::query()->find($request->id);
        $message = [
            'type' => 'error',
            'value' => 'El comentario no existe.'
        ];
        if ($comment->first()) {
            $comment->delete();
            $message['type'] = 'success';
            $message['value'] = 'El comentario se ha borrado.';
        }

        return $this->getAllComments($message);
    }
}
