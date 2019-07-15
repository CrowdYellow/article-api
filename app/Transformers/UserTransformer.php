<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'              => $user->id,
            'name'            => $user->name,
            'phone'           => $user->phone,
            'avatar'          => env('APP_URL').$user->avatar,
            'introduction'    => $user->introduction,
            'created_at'      => (string)$user->created_at,
            'updated_at'      => (string)$user->updated_at,
        ];
    }
}