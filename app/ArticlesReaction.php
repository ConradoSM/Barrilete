<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticlesReaction extends Model
{
    /**
     * @var string
     */
    protected $table = 'articles_reactions';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'article_id', 'section_id', 'reaction',
    ];

    /**
     * Get Reactions
     * @param $query
     * @param $articleId
     * @param $sectionId
     * @param $reaction
     * @return mixed
     */
    public function scopeReactions($query, $articleId, $sectionId, $reaction)
    {
        return $query->where('article_id', $articleId)
            ->where('section_id', $sectionId)
            ->where('reaction', $reaction);
    }
}
