<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollOptions extends Model {

    protected $table = 'poll_options';
    protected $fillable = [
        'poll_id', 'option',
    ];

    /**
     * @return BelongsTo
     */
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeOptions($query, $id) {

        return $query->whereId($id)->first();

    }
}
