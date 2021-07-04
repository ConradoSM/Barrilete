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
     * @return HasMany
     */
    public function articles() : HasMany
    {
        return $this->hasMany(Articles::class, 'section_id')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);
    }

    /**
     * @return HasMany
     */
    public function galleries() : HasMany
    {
        return $this->hasMany(Gallery::class, 'section_id')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(15);
    }

    /**
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeSearchSection($query, $name)
    {
        return $query->where('name',$name)->firstOrFail();
    }
}
