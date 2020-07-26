<?php

namespace barrilete\Http\Controllers;

use Illuminate\Contracts\View\Factory;
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
            $comment->parent_id = $request->parent_id ? $request->parent_id : null;
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
                'view' => $this->get($request->article_id, $request->section_id)->render(),
                'count' => Comments::articles($request->article_id, $request->section_id)->count(),
                'success' => $request->parent_id ? 'Tu respuesta se ha publicado.' : 'El comentario se ha publicado.'
            ])->header('Content-Type', 'application/json');
        }

        return abort(500);
    }

    /**
     * Get Comments
     * @param $id
     * @param $section_id
     * @return Factory|View
     */
    public function get($id, $section_id)
    {
        $comments = Comments::articles($id, $section_id)
            ->orderBy('id','DESC')
            ->paginate(10)
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
                    'success' => 'El comentario se ha borrado.'
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
                    'view' => $this->get($request->article_id, $request->section_id)->render(),
                    'count' => Comments::articles($request->article_id, $request->section_id)->count(),
                    'success' => 'El comentario se ha actualizado.'
                ])->header('Content-Type', 'application/json');
            }

            return response()->json(['error' => 'El comentario no existe'],404);
        }

        return abort(404);
    }
}
