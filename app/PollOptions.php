<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class PollOptions extends Model {

    protected $table = 'poll_options';
    protected $fillable = [
        'poll_id', 'option',
    ];

    public function scopeOptions($query, $id) {
        
        return $query->whereId($id)->first();
   
    }   
}
