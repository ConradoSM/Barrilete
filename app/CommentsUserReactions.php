<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class CommentsUserReactions extends Model
{
    /**
     * @var string
     */
    protected $table = 'comments_user_reactions';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'comment_id', 'reaction'
    ];

    /**
     * Reaction Exist
     * @param $query
     * @param $userId
     * @param $commentId
     * @return mixed
     */
    public function scopeReactionExist($query, $userId, $commentId)
    {
        return $query->where('user_id', $userId)->where('comment_id', $commentId)->first();
    }
}
