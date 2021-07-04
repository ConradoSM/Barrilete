<?php

namespace barrilete;

use Carbon\Carbon;
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
     * User Poll
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Poll Section
     * @return BelongsTo
     */
    public function section() : BelongsTo
    {
        return $this->belongsTo(Sections::class);
    }

    /**
     * Poll Options
     * @return HasMany
     */
    public function option() : HasMany
    {
        return $this->hasMany(PollOptions::class)->orderBy('votes','desc');
    }

    /**
     * Polls in Homepage
     * @param $query
     * @return mixed
     */
    public function scopePollsHome($query)
    {
        return $query->where('status','PUBLISHED')
            ->whereDate('valid_from','<=', Carbon::now())
            ->latest()
            ->take(3)
            ->get();
    }

    /**
     * Find Poll And Increment Views + 1
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
     * Show Rest Of Polls
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeMorePolls($query, $id)
    {
        return $query->whereDate('valid_from','<=', Carbon::now())
            ->where('id','!=',$id)
            ->where('status','PUBLISHED')
            ->latest()
            ->take(8)
            ->get();
    }

    /**
     * Poll Search
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
     * Poll Search in Dashboard
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
     * No Published Polls
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
     * Get Poll Comments
     * @param $sectionId
     * @return HasMany
     */
    public function comments($sectionId) : HasMany
    {
        return $this->hasMany(Comments::class,'article_id')
            ->where('section_id', $sectionId);
    }

    /**
     * Users Poll Reactions
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
