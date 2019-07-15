<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Transformers\MessageTransformer;

class MessagesController extends Controller
{
    protected $messageTransformer;

    public function __construct(MessageTransformer $messageTransformer)
    {
        $this->messageTransformer = $messageTransformer;
    }

    /**
     * 消息列表
     * @return mixed
     */
    public function index()
    {
        // 当前用户
        $user = $this->user();

        $messages = Message::where('from_user_id', $user->id)
            ->orWhere('to_user_id', $user->id)
            ->get()
            ->groupBy('dialog_id');
        $data     = [];
        $account  = [];
        foreach ($messages as $messageGroup) {
            if ($user->id == $messageGroup->last()->from_user_id) {
                $account['user_id'] = $messageGroup->last()->to_user_id;
                $account['name']    = $messageGroup->last()->toUser->name;
                $account['avatar']  = $messageGroup->last()->toUser->avatar;
            } else {
                $account['user_id'] = $messageGroup->last()->from_user_id;
                $account['name']    = $messageGroup->last()->fromUser->name;
                $account['avatar']  = $messageGroup->last()->fromUser->avatar;
            }
            $account['body']       = $messageGroup->last()->body;
            $account['dialog_id']  = $messageGroup->last()->dialog_id;
            $account['has_read']  = $messageGroup->last()->has_read;
            $account['created_at'] = (string)$messageGroup->last()->created_at;
            $data[]                = $account;
        }

        return $this->data(config('code.success'), 'success', $data);
    }

    /**
     * 对话框列表
     * @param $id
     * @return mixed
     */
    public function chatWithUser($id)
    {
        // 当前用户
        $user = $this->user();

        if ($id == $user->id) {
            return $this->data(config('code.refuse_err'), '调皮，不要自言自语！');
        }

        $arr = [$user->id, $id];

        sort($arr);

        $dialog_id = (string)$arr[0] . $arr[1];

        $messages = Message::where('dialog_id', $dialog_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $messages->markAsRead();

        $data = [];
        foreach ($messages as $message) {
            $data[] = $this->messageTransformer->transform($message);
        }

        return $this->data(config('code.success'), 'success', $data);
    }
}
