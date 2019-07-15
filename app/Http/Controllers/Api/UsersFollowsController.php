<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Notifications\UserFollow;
use App\Transformers\UserTransformer;

class UsersFollowsController extends Controller
{
    protected $userTransformer;

    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    /**
     * 关注列表
     * @return mixed
     */
    public function followed()
    {
        // 当前用户
        $user = $this->user();

        $users = $user->followers()->paginate(10);

        $data = [];
        foreach ($users as $user) {
            $data[] = $this->userTransformer->transform($user);
        }

        return $this->data(config('code.success'), 'success', $data);
    }

    /**
     * 粉丝列表
     * @return mixed
     */
    public function follower()
    {
        // 当前用户
        $user = $this->user();

        $users = $user->followersUser()->paginate(10);

        $data = [];
        foreach ($users as $user) {
            $data[] = $this->userTransformer->transform($user);
        }

        return $this->data(config('code.success'), 'success', $data);
    }

    /**
     * 检查用户是否关注某个用户
     * @param $id
     * @return mixed
     */
    public function hasFollowedThis($id)
    {
        // 当前用户
        $currentUser = $this->user();

        // 判断当前用户是否关注过该用户
        if ($currentUser->hasFollowedThis($id)) {

            return $this->data(config('code.success'), '已关注', ['followed' => true]);
        }

        return $this->data(config('code.success'), '没有关注', ['followed' => false]);
    }

    /**
     * 关注 取消关注
     * @param $id
     * @return mixed
     */
    public function followedThis($id)
    {
        // 当前用户
        $currentUser = $this->user();

        // 被关注用户
        $user = User::find($id);

        // 判断关注用户是否是当前用户
        if ($currentUser->id == $user->id) {

            return $this->data(config('code.refuse_err'), '不可以关注自己');
        }

        // 关注或者取消关注该用户
        $followed = $currentUser->followThisUser($user->id);

        //判断执行的是添加还是删除操作
        if (count($followed['attached']) > 0) {

            // 粉丝加一
            $user->increment('fans');

            // 关注用户加一
            $currentUser->increment('follow_count');

            // 通知用户
            $user->notify(new UserFollow($currentUser));

            return $this->data(200, '成功关注', ['followed' => true]);
        }

        $user->decrement('fans');

        $currentUser->decrement('follow_count');

        return $this->data(200, '取消关注', ['followed' => false]);
    }
}
