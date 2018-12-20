<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model {

    protected $table = 'poll';
    
    public function scopePollHome($query) {
        
        return $query->select('id', 'title', 'date')
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(3);      
    }
    
    public function scopePoll($query, $id) {
        
        $query->whereId($id)->where('status','PUBLISHED'); 
        $query->increment('views',1);
        
        return $query;
    }
    
    public function option() {

        return $this->hasMany(PollOptions::class, 'poll_id');
    }
    
    public function scopeSearchOptions($query, $id) {
        
        return $query->where('id',$id);
  
    }
    
    public function scopeMorePolls($query, $id) {
        
        return $query->select('id', 'title')
        ->where('id','!=',$id)
        ->where('status','PUBLISHED')
        ->orderBy('id','DESC')
        ->limit(8);      
    }
    
    public function user() {

        return $this->belongsTo(User::class);
    }

    public function section() {

        return $this->belongsTo(Sections::class);
    }
    
    public function scopeSearch($query, $busqueda) {

        return $query->whereRaw("MATCH (title,article_desc) AGAINST (? IN BOOLEAN MODE)", array($busqueda))
        ->where('status','PUBLISHED')
        ->orderBy('id', 'DESC')
        ->paginate(10);
    }
}