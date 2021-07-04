<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Newsletter extends Model
{
    /**
     * @var string
     */
    protected $table = 'newsletters';

    /**
     * @var array
     */
    protected $fillable = [
        'email', 'user_id', 'status', 'additional_information'
    ];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
