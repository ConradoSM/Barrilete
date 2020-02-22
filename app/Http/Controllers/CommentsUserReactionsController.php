<?php

namespace barrilete\Http\Controllers;

use barrilete\Comments;
use Illuminate\Http\Request;
use barrilete\CommentsUserReactions;

class CommentsUserReactionsController extends Controller
{
    /**
     * @param Request $request
     * @return mixed|null
     */
    public function save(Request $request)
    {
        $existReaction = CommentsUserReactions::reactionExist($request->user_id, $request->comment_id);
        if (!$existReaction->first()) {
            $reaction = new CommentsUserReactions();
            $reaction->user_id = $request->user_id;
            $reaction->comment_id = $request->comment_id;
            $reaction->reaction = $request->reaction == 1 ? true : false;
            $reaction->save();
        } elseif ($existReaction and $request->reaction != $existReaction->reaction) {
            $existReaction->reaction = $request->reaction;
            $existReaction->save();
        } elseif ($existReaction and $request->reaction == $existReaction->reaction) {
            $existReaction->delete();
            $request->reaction = null;
        }
        $totalsReactions = $this->getTotalReactions($request->comment_id);
        return array_merge($totalsReactions, ['reaction' => $request->reaction]);
    }

    /**
     * @param $commentId
     * @return array
     */
    public function getTotalReactions($commentId)
    {
        $comment = Comments::find($commentId);
        return [
            'likes' => $comment->getTotalLikes->count(),
            'dislikes' => $comment->getTotalDislikes->count()
        ];
    }
}
