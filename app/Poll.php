<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    /**
     * @var string
     */
    protected $table = 'poll';

    /**
     * UNA ENCUESTA PERTENECE A UN USUARIO
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * UNA ENCUESTA PERTENECE A UNA SECCIÓN
     * @return BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Sections::class);
    }

    /**
     * UNA ENCUESTA TIENE MUCHAS OPCIONES
     * @return HasMany
     */
    public function option()
    {
        return $this->hasMany(PollOptions::class)->orderBy('votes','desc');
    }

    /**
     * ENCUESTAS QUE SE MUESTRAN EN LA HOMEPAGE
     * @param $query
     * @return mixed
     */
    public function scopePollsHome($query)
    {
        return $query->where('status','PUBLISHED')
        ->latest()
        ->take(3)
        ->get();
    }

    /**
     * BUSCA LA ENCUESTA POR EL ID, LA MUESTRA Y ACTUALIZA LAS VISITAS
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopePoll($query, $id)
    {
        $query->findOrFail($id)->where('status','PUBLISHED');
        $query->increment('views',1);
        return $query->first();
    }

    /**
     * MUESTRA EL RESTO DE LAS ENCUESTAS
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeMorePolls($query, $id)
    {
        return $query->select('id','title','created_at')
        ->where('id','!=',$id)
        ->where('status','PUBLISHED')
        ->latest()
        ->take(8)
        ->get();
    }

    /**
     * BÚSQUEDA DE ENCUESTAS
     * @param $query
     * @param $busqueda
     * @return mixed
     */
    public function scopeSearch($query, $busqueda)
    {
        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC');
    }

    /**
     * BÚSQUEDA DE ENCUESTAS USUARIOS
     * @param $query
     * @param $busqueda
     * @param $author
     * @return mixed
     */
    public function scopeSearchAuth($query, $busqueda, $author)
    {
        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('user_id', $author)
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }

    /**
     * ENCUESTAS NO PUBLICADAS
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
