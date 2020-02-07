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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIONA LA GALERÍA CON LA SECCIÓN
     * @return BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Sections::class);
    }

    /**
     * GALERÍA DE FOTOS DE LA HOMEPAGE
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
     * BUSCA LA LISTA DE GALERÍAS
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
     * RELACIONA LA GALERÍA CON LAS FOTOS CARGADAS
     * @return HasMany
     */
    public function photos()
    {
        return $this->hasMany(GalleryPhotos::class);
    }

    /**
     * BUSCA LA GALERÍA POR ID
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
     * BUSQUEDA DE GALERÍAS
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
     * BÚSQUEDA DE GALERIAS USUARIOS
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
     * GALERÍAS NO PUBLICADAS
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
     * @param $sectionId
     * @return HasMany
     */
    public function comments($sectionId)
    {
        return $this->hasMany(Comments::class,'article_id')
            ->where('section_id', $sectionId);
    }
}
