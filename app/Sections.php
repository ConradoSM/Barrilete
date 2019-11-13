<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sections extends Model
{
    /**
     * @var string
     */
    protected $table = 'sections';

    /**
     * RELACIONA LOS ARTÍCULOS VINCULADOS A UNA SECCIÓN
     * @return HasMany
     */
    public function articles()
    {
        return $this->hasMany(Articles::class, 'section_id')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);
    }

    /**
     * RELACIONA LAS GALERIAS VINCULADAS A UNA SECCIÓN
     * @return HasMany
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'section_id')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);
    }

    /**
     * BUSCA LA SECCIÓN POR SU NOMBRE
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeSearchSection($query, $name)
    {
        return $query->where('name',$name)->firstOrFail();
    }
}
