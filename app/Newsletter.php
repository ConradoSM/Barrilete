<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

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
}
