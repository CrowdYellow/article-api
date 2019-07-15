<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Transformers\UserTransformer;

class UsersController extends Controller
{
    protected $userTransformer;

    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function me()
    {
        // 当前用户
        $user = $this->user();

        $data = $this->userTransformer->transform($user);

        return $this->data(config('code.success'), 'success', $data);
    }
}
