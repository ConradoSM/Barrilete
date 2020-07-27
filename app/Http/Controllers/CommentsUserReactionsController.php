<?php

namespace barrilete\Http\Controllers;

use barrilete\Articles;
use barrilete\Comments;
use barrilete\Gallery;
use barrilete\Notifications\UsersCommentReaction;
use barrilete\Notifications\UsersCommentReply;
use barrilete\Poll;
use barrilete\User;
use Illuminate\Http\Request;
use barrilete\CommentsUserReactions;
use Illuminate\Support\Facades\Auth;

class CommentsUserReactionsController extends Controller
{
    /**
     * Save Reaction
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
        if ($request->reaction != null) {
            $comment = Comments::query()->find($request->comment_id);
            $this->sendNotification(Auth::user(), $comment->user, $comment->section->name, $comment->article_id, $request->reaction, 'reaction');
        }

        return array_merge($totalsReactions, ['reaction' => $request->reaction]);
    }

    /**
     * Get Total Reactions
     * @param $commentId
     * @return array
     */
    public function getTotalReactions($commentId)
    {
        $comment = Comments::query()->find($commentId);

        return [
            'likes' => $comment->getTotalLikes->count(),
            'dislikes' => $comment->getTotalDislikes->count()
        ];
    }

    /**
     * Send Notification
     * @param $fromUser
     * @param $toUser
     * @param $sectionName
     * @param $articleId
     * @param $reaction
     * @param $type
     */
    public function sendNotification($fromUser, $toUser, $sectionName, $articleId, $reaction, $type)
    {
        if ($fromUser != $toUser) {
            $routeName = 'article';
            $title = Articles::query()->find($articleId) ? Articles::query()->find($articleId)->title : '';
            if ($sectionName == 'galerias') {
                $routeName = 'gallery';
                $title = Gallery::query()->find($articleId)->title;
            }
            if ( $sectionName == 'encuestas') {
                $routeName = 'poll';
                $title = Poll::query()->find($articleId)->title;
            }
            $options = [
                'id' => $articleId,
                'section' => str_slug($sectionName),
                'title' => str_slug($title,'-')
            ];
            $link = route($routeName, $options);
            $reaction = [
                'from' => $fromUser->name,
                'to' => $toUser->name,
                'link' => $link,
                'reaction' => $reaction
            ];
            if ($type == 'reply') {
                $toUser->notify(new UsersCommentReply($reaction));
            }
            if ($type == 'reaction') {
                $toUser->notify(new UsersCommentReaction($reaction));
            }
        }
    }
}
