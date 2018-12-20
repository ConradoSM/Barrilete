<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class PollOptions extends Model {

    protected $table = 'poll_options';

    public function scopeOptions($query, $id) {
        
        return $query->whereId($id)->first();
   
    }   
}
