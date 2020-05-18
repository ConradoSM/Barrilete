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
}
