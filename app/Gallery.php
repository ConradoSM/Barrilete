<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    /**
     * @var string
     */
    protected $table = 'gallery';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'section_id', 'author', 'article_desc',
    ];

    /**
     * RELACIONA LA GALERÍA CON EL USUARIO QUE LA CARGÓ
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get Section
     * @return BelongsTo
     */
    public function section() : BelongsTo
    {
        return $this->belongsTo(Sections::class);
    }

    /**
     * Gallery Home
     * @param $query
     * @return mixed
     */
    public function scopeGalleryHome($query)
    {
        return $query->where('status','PUBLISHED')
        ->latest()
        ->take(1)
        ->get();
    }

    /**
     * Get Published Galleries
     * @param $query
     * @return mixed
     */
    public function scopeGalleries($query)
    {
        return $query->where('status','PUBLISHED')
        ->get()
        ->sortByDesc('id');
    }

    /**
     * Gallery Photos
     * @return HasMany
     */
    public function photos() : HasMany
    {
        return $this->hasMany(GalleryPhotos::class);
    }

    /**
     * Get Gallery By Id
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeGallery($query, $id)
    {
        $query->findOrFail($id)->where('status','PUBLISHED');
        $query->increment('views',1);

        return $query->first();
    }

    /**
     * Galleries Search
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($search))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC');
    }

    /**
     * Galleries Search in Dashboard
     * @param $query
     * @param $search
     * @param $author
     * @return mixed
     */
    public function scopeSearchAuth($query, $search, $author)
    {
        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($search))
        ->where('user_id', $author)
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }

    /**
     * Unpublished Galleries
     * @param $query
     * @return mixed
     */
    public function scopeUnpublished($query)
    {
        return $query->select('id','title','article_desc','views','status','created_at')
        ->where('status','DRAFT')
        ->orderBy('id','desc')
        ->paginate(10);
    }

    /**
     * Gallery Comments
     * @param $sectionId
     * @return HasMany
     */
    public function comments($sectionId) : HasMany
    {
        return $this->hasMany(Comments::class,'article_id')
            ->where('section_id', $sectionId);
    }

    /**
     * Users Gallery Reactions
     * @param $sectionId
     * @param $reaction
     * @return HasMany
     */
    public function reactions($sectionId, $reaction) : HasMany
    {
        return $this->hasMany(ArticlesReaction::class, 'article_id')
            ->where('section_id', $sectionId)
            ->where('reaction', $reaction);
    }
}
