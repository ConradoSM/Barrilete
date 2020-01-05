<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            ->whereNull('parent_id')
            ->orderBy('id','DESC')
            ->paginate(10)
            ->onEachSide(1);
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
}
