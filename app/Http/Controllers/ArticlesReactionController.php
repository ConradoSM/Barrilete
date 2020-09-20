<?php

namespace barrilete\Http\Controllers;

use Auth;
use barrilete\Articles;
use barrilete\ArticlesReaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticlesReactionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse | void
     * @throws Exception
     */
    public function save(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::id()) {
                $articleId = $request->article_id;
                $sectionId = $request->section_id;
                $userId = $request->user_id;
                $userReaction = $request->user_reaction;

                $reaction = ArticlesReaction::query()
                    ->where('article_id', $articleId)
                    ->where('section_id', $sectionId)
                    ->where('user_id', $userId)
                    ->first();

                if (!$reaction) {
                    $reaction = new ArticlesReaction();
                    $reaction->user_id = $userId;
                    $reaction->article_id = $articleId;
                    $reaction->section_id = $sectionId;
                    $reaction->reaction = $userReaction;
                    $reaction->save();
                } elseif ($reaction && $reaction->reaction == $userReaction) {
                    $reaction->delete();
                    $userReaction = null;
                } else {
                    $reaction->reaction = $userReaction;
                    $reaction->save();
                }

                return response()->json([
                    'likes' => ArticlesReaction::reactions($articleId, $sectionId, 1)->count(),
                    'dislikes' => ArticlesReaction::reactions($articleId, $sectionId, 0)->count(),
                    'reaction' => $userReaction
                ]);
            }

            return response()->json([
                'error' => 'Para reaccionar al artículo debes estar logueado.'
            ]);
        }

        return abort(404);
    }
}
