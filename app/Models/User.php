<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }

        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'avatar', 'introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 用户的关注
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(self::class, 'user_follows', 'follower_id', 'followed_id')->withTimestamps();
    }

    /**
     * 用户的粉丝
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followersUser()
    {
        return $this->belongsToMany(self::class, 'user_follows', 'followed_id', 'follower_id')->withTimestamps();
    }

    /**
     * 判断用户是否关注该用户
     * @param $user
     * @return bool
     */
    public function hasFollowedThis($user)
    {
        return !!$this->followers()->where('followed_id', $user)->count();
    }

    /**
     * 关注用户 取消关注
     *
     * @param $user
     * @return array
     */
    public function followThisUser($user)
    {
        return $this->followers()->toggle($user);
    }

    /**
     * 文章
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * 文章数量
     */
    public function articlesCount()
    {
        $this->article_count = $this->articles->count();
        $this->save();
    }

    /**
     * 文章的点赞
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articleVotes()
    {
        return $this->belongsToMany(Article::class, 'article_votes')->withTimestamps();
    }

    /**
     * 是否已点赞
     * @param $article
     * @return bool
     */
    public function hasVotedThisArticle($article)
    {
        return !!$this->articleVotes()->where('article_id', $article)->count();
    }

    /**
     * 文章点赞 取消点赞
     * @param $article
     * @return array
     */
    public function votedThisArticle($article)
    {
        return $this->articleVotes()->toggle($article);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
}
