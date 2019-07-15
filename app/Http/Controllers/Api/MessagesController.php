<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\MessageRequest;
use App\Models\Message;
use App\Transformers\MessageTransformer;
use Illuminate\Http\Request;

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
        // 消息列表
        $data     = [];
        // 每个聊天者
        $account  = [];
        foreach ($messages as $messageGroup) {
            // 判断最后发消息的人是不是当前用户
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

        // 不能与自己私聊
        if ($id == $user->id) {
            return $this->data(config('code.refuse_err'), '调皮，不要自言自语！');
        }

        $arr = [$user->id, $id];

        sort($arr);

        // 获取对话id
        $dialog_id = (string)$arr[0] . $arr[1];

        // 获取两个人的所有聊天记录
        $messages = Message::where('dialog_id', $dialog_id)
            ->orderBy('created_at', 'asc')
            ->get();

        // 将消息变为已读状态
        $messages->markAsRead();

        $data = [];
        foreach ($messages as $message) {
            $data[] = $this->messageTransformer->transform($message);
        }

        return $this->data(config('code.success'), 'success', $data);
    }

    public function store(MessageRequest $request, $id)
    {
        // 当前用户
        $user = $this->user();

        $arr = [$user->id, $id];

        sort($arr);

        // 获取对话id
        $dialog_id = (string)$arr[0] . $arr[1];

        $message               = new Message();
        $message->to_user_id   = $id;
        $message->from_user_id = $user->id;
        $message->body         = $request->body;
        $message->dialog_id    = $dialog_id;
        $message->save();

        return $this->data(config('code.success'), 'success');
    }
}
