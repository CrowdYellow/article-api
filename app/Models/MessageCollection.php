<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MessageCollection extends Collection
{
    /**
     * Mark a notifications collection as read.
     */
    public function markAsRead()
    {
        $this->each(function($message) {
            //判断是否是该用户的收信
            if($message->to_user_id === Auth::id() ){
                $message->markAsRead();
            }
        });
    }
}