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
     * RELACION UN ARTÍCULO A UN USUARIO
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIÓN UN ARTÍCULO A UNA SECCIÓN
     * @return BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Sections::class);
    }

    /**
     * ARTÍCULOS QUE SE VAN A MOSTRAR EN LA HOMEPAGE
     * @param $query
     * @return mixed
     */
    public function scopeArticlesHome($query)
    {
        return $query->where('status','PUBLISHED')
        ->latest()
        ->take(24)
        ->get()
        ->sortByDesc(
            function($post)
            {
                return sprintf('%-12s%s',$post->section->prio, $post->created_at);
            });
    }

    /**
     * ARTÍCULO QUE SE VA A MOSTRAR SEGÚN EL ID
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
     * RESTO DE LOS ARTÍCULOS QUE SE VAN A MOSTRAR
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
     * BÚSQUEDA DE ARTÍCULOS
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
     * BÚSQUEDA DE ARTÍCULOS USUARIOS
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
     * ARTÍCULOS NO PUBLICADOS
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
     * @param $sectionId
     * @return HasMany
     */
    public function comments($sectionId)
    {
        return $this->hasMany(Comments::class,'article_id')
            ->where('section_id', $sectionId);
    }

    /**
     * @param $sectionId
     * @param $reaction
     * @return HasMany
     */
    public function reactions($sectionId, $reaction)
    {
        return $this->hasMany(ArticlesReaction::class, 'article_id')
            ->where('section_id', $sectionId)
            ->where('reaction', $reaction);
    }
}
