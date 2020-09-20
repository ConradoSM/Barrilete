<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comments extends Model
{
    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $fillable = [
        'content', 'parent_id', 'section_id', 'article_id', 'user_id',
    ];

    /**
     * @param $query
     * @param $article_id
     * @param $section_id
     * @return mixed
     */
    public function scopeArticles($query, $article_id, $section_id)
    {
        return $query->where('article_id', $article_id)
            ->where('section_id', $section_id)
            ->whereNull('parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Sections::class, 'section_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Comments::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function replies()
    {
        return $this->hasMany(Comments::class, 'parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get User Reaction
     * @param $userId
     * @return Model|HasOne|object|null
     */
    public function getUserReaction($userId)
    {
        return $this->hasOne(CommentsUserReactions::class, 'comment_id')
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Get Totals Likes
     * @return HasMany
     */
    public function getTotalLikes()
    {
        return $this->hasMany(CommentsUserReactions::class, 'comment_id')
            ->where('reaction', '1');
    }

    /**
     * Get Totals Dislikes
     * @return HasMany
     */
    public function getTotalDislikes()
    {
        return $this->hasMany(CommentsUserReactions::class, 'comment_id')
            ->where('reaction', '0');
    }
}
