<?php

namespace barrilete;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Roles
     */
    const ADMIN_USER_ROLE = 'admin';
    const DEFAULT_USER_ROLE = 'user';
    const EDITOR_USER_ROLE = 'editor';

    /**
     * @var string
     */
    protected $table = 'users';
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User Articles Relation
     * @return HasMany
     */
    public function articles() : HasMany
    {
       return $this->hasMany(Articles::class);
    }

    /**
     * User Gallery Relation
     * @return HasMany
     */
    public function gallery() : HasMany
    {
       return $this->hasMany(Gallery::class);
    }

    /**
     * User Poll Relation
     * @return HasMany
     */
    public function poll() : HasMany
    {
       return $this->hasMany(Poll::class);
    }

    /**
     * User Roles
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Users Roles
     * @param $roles
     * @return bool
     */
    public function authorizeRoles($roles) : bool
    {
        return $this->hasAnyRole($roles);
    }

    /**
     * Has Any Role Method
     * @param $roles
     * @return bool
     */
    public function hasAnyRole($roles) : bool
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Has Role Method
     * @param $role
     * @return bool
     */
    public function hasRole($role) : bool
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }

        return false;
    }

    /**
     * @param $commentId
     * @return HasOne
     */
    public function getCommentReaction($commentId) : HasOne
    {
        return $this->hasOne(CommentsUserReactions::class, 'user_id')->where('comment_id', $commentId);
    }

    /**
     * @return string
     */
    public function receivesBroadcastNotificationsOn() : string
    {
        return 'Barrilete.User.'.$this->id;
    }

    /**
     * Get Inbox Messages
     * @return LengthAwarePaginator
     */
    public function inboxMessages() : LengthAwarePaginator
    {
        return $this->hasMany(Messages::class, 'to')
            ->orderBy('id', 'DESC')
            ->latest()
            ->groupBy(['from'])
            ->paginate(10);
    }

    /**
     * Get Outbox Messages
     * @return LengthAwarePaginator
     */
    public function outboxMessages() : LengthAwarePaginator
    {
        return $this->hasMany(Messages::class, 'from')
            ->orderBy('id', 'DESC')
            ->latest()
            ->groupBy(['to'])
            ->paginate(10);
    }

    /**
     * Get Comment Notifications
     * @return Collection
     */
    public function getCommentNotifications() : Collection
    {
        return $this->notifications()
            ->whereIn('type', [
                'barrilete\Notifications\UsersCommentReply',
                'barrilete\Notifications\UsersCommentReaction'
            ])->get()->groupBy(function($item) {
                return $item->data['from'];
            });
    }

    /**
     * Get Unread Comment Notifications Count
     * @return int
     */
    public function getUnreadCommentNotificationsCount() : int
    {
        return $this->unreadNotifications()
            ->whereIn('type', [
                'barrilete\Notifications\UsersCommentReply',
                'barrilete\Notifications\UsersCommentReaction'
            ])->count();
    }

    /**
     * Get Message Notifications
     * @return Collection
     */
    public function getMessageNotifications() : Collection
    {
        return $this->notifications()
            ->where('type','barrilete\Notifications\UsersMessages')
            ->get()
            ->groupBy(function($item) {
                return $item->data['from'];
            });
    }

    /**
     * Get Unread Message Notifications Count
     * @return int
     */
    public function getUnreadMessageNotificationsCount() : int
    {
        return $this->unreadNotifications()
            ->where('type','barrilete\Notifications\UsersMessages')->count();
    }

    /**
     * Get User Article Reaction
     * @param $articleId
     * @param $sectionId
     * @return HasOne
     */
    public function articleReaction($articleId, $sectionId) : HasOne
    {
        return $this->hasOne(ArticlesReaction::class, 'user_id')
            ->where('article_id', $articleId)
            ->where('section_id', $sectionId);
    }

    /**
     * Is Newsletter Subscribe
     * @return boolean
     */
    public function isNewsletterSubscribe() : bool
    {
        $newsletter = $this->hasOne(Newsletter::class, 'user_id');
        if ($newsletter->first()) {
            if ($newsletter->first()->status) {
                return true;
            }
        }
        return false;
    }
}
