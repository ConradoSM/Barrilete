<?php

namespace barrilete;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    public function parent()
    {
        return $this->belongsTo(Messages::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function replies()
    {
        return $this->hasMany(Messages::class, 'parent_id');
    }


    /**
     * @return BelongsTo
     */
    public function getSender()
    {
        return $this->belongsTo(User::class, 'from')->first();
    }

    /**
     * @return BelongsTo
     */
    public function getRecipient()
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
