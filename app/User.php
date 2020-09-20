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
    public function articles()
    {
       return $this->hasMany(Articles::class);
    }

    /**
     * User Gallery Relation
     * @return HasMany
     */
    public function gallery()
    {
       return $this->hasMany(Gallery::class);
    }

    /**
     * User Poll Relation
     * @return HasMany
     */
    public function poll()
    {
       return $this->hasMany(Poll::class);
    }

    /**
     * User Roles
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Users Roles
     * @param $roles
     * @return bool
     */
    public function authorizeRoles($roles)
    {
        return $this->hasAnyRole($roles);
    }

    /**
     * Has Any Role Method
     * @param $roles
     * @return bool
     */
    public function hasAnyRole($roles)
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
    public function hasRole($role)
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
    public function getCommentReaction($commentId)
    {
        return $this->hasOne(CommentsUserReactions::class, 'user_id')->where('comment_id', $commentId);
    }

    /**
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'Barrilete.User.'.$this->id;
    }

    /**
     * Get Inbox Messages
     * @return LengthAwarePaginator
     */
    public function inboxMessages()
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
    public function outboxMessages()
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
    public function getCommentNotifications()
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
    public function getUnreadCommentNotificationsCount()
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
    public function getMessageNotifications()
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
    public function getUnreadMessageNotificationsCount()
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
    public function articleReaction($articleId, $sectionId)
    {
        return $this->hasOne(ArticlesReaction::class, 'user_id')
            ->where('article_id', $articleId)
            ->where('section_id', $sectionId);
    }

    /**
     * Is Newsletter Subscribe
     * @return boolean
     */
    public function isNewsletterSubscribe()
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
