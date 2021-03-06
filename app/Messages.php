<?php

namespace barrilete;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Messages extends Model
{
    use Notifiable;

    /**
     * @var string
     */
    protected $table = 'messages';
    /**
     * @var array
     */

    protected $fillable = [
        'from', 'to', 'subject', 'body', 'status'
    ];

    /**
     * @return BelongsTo
     */
    public function parent() : BelongsTo
    {
        return $this->belongsTo(Messages::class, 'parent_id');
    }

    /**
     * @return LengthAwarePaginator
     */
    public function replies() : LengthAwarePaginator
    {
        return $this->hasMany(Messages::class, 'parent_id')->orderByDesc('id')->paginate(10);
    }


    /**
     * @return BelongsTo
     */
    public function getSender() : BelongsTo
    {
        return $this->belongsTo(User::class, 'from')->first();
    }

    /**
     * @return BelongsTo
     */
    public function getRecipient() : BelongsTo
    {
        return $this->belongsTo(User::class, 'to')->first();
    }

    /**
     * Mark as read message
     */
    public function markAsRead()
    {
        if (!$this->status) {
            $this->forceFill(['status' => true])->save();
        }
    }
}
