<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class PollIp extends Model
{
    protected $fillable = ['poll_id','ip'];
    protected $table = 'poll_ip';
}
