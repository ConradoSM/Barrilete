<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Articles extends Model
{
    /**
     * @var string
     */
    protected $table = 'articles';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'section_id', 'author', 'article_desc', 'photo', 'video', 'article_body',
    ];

    /**
     * User Article
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Article Section
     * @return BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Sections::class);
    }

    /**
     * Home Articles
     * @param $query
     * @return mixed
     */
    public function scopeArticlesHome($query)
    {
        return $query->where('status','PUBLISHED')->where('is_breaking', false)
            ->latest()->take(24)->get()->sortByDesc(
            function($post) {
                return sprintf('%-12s%s',$post->section->prio, $post->created_at);
            });
    }

    /**
     * Get Article
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeShowArticle($query, $id)
    {
        $query->findOrFail($id)->where('status','PUBLISHED');
        $query->increment('views',1);

        return $query->first();
    }

    /**
     * More Articles
     * @param $query
     * @param $id
     * @param $section
     * @return mixed
     */
    public function scopeMoreArticles($query, $id, $section)
    {
        return $query->select('id', 'title', 'photo')
        ->where('id','!=',$id)
        ->where('section_id',$section)
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(8)
        ->get();
    }

    /**
     * Articles Search
     * @param $query
     * @param $busqueda
     * @return mixed
     */
    public function scopeSearch($query, $busqueda)
    {
        return $query->whereRaw("MATCH (title,article_desc,article_body) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC');
    }

    /**
     * Articles Search in Dashboard
     * @param $query
     * @param $busqueda
     * @param $author
     * @return mixed
     */
    public function scopeSearchAuth($query, $busqueda, $author)
    {
        return $query->whereRaw("MATCH (title,article_desc,article_body) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('user_id', $author)
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }

    /**
     * Unpublished Articles
     * @param $query
     * @return mixed
     */
    public function scopeUnpublished($query)
    {
        return $query->where('status','DRAFT')
        ->orderBy('id','desc')
        ->paginate(10);
    }

    /**
     * Article Comments
     * @param $sectionId
     * @return HasMany
     */
    public function comments($sectionId = null)
    {
        if ($sectionId) {
            return $this->hasMany(Comments::class,'article_id')
                ->where('section_id', $sectionId);
        }

        return $this->hasMany(Comments::class,'article_id')
            ->whereColumn('comments.section_id', '=','articles.section_id');
    }

    /**
     * Users Article Reactions
     * @param $sectionId
     * @param $reaction
     * @return HasMany
     */
    public function reactions($sectionId = null, $reaction = null)
    {
        if ($sectionId) {
            return $this->hasMany(ArticlesReaction::class, 'article_id')
                ->where('section_id', $sectionId)
                ->where('reaction', $reaction);
        }

        return $this->hasMany(ArticlesReaction::class, 'article_id')
            ->whereColumn('articles_reactions.section_id', '=','articles.section_id')
            ->where('articles_reactions.reaction','1');
    }
}
