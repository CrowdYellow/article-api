<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CommentRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Transformers\CommentTransformer;

class CommentsController extends Controller
{
    protected $commentTransformer;

    public function __construct(CommentTransformer $commentTransformer)
    {
        $this->commentTransformer = $commentTransformer;
    }

    /**
     * 评论列表
     * @param $id
     * @return mixed
     */
    public function getCommentsBy($id)
    {
        $comments = Comment::where('article_id', $id)->orderBy('created_at', 'asc')->paginate(20);
        $data     = [];
        foreach ($comments as $comment) {
            $data[] = $this->commentTransformer->transform($comment);
        }
        return $this->data(config('code.success'), 'success', $data);
    }

    /**
     * 创建评论
     * @param CommentRequest $request
     * @return mixed
     */
    public function store(CommentRequest $request)
    {
        // 当前用户
        $user = $this->user();

        $article = Article::find($request->article_id);

        if (empty($article)) {
            return $this->data(config('code.refuse_err'), '文章不存在！');
        }

        if (!$article->is_allowed_comment) {
            return $this->data(config('code.refuse_err'), '该文章不能评论！');
        }

        $comment             = new Comment();
        $comment->text       = $request->text;
        $comment->user_id    = $user->id;
        $comment->article_id = $request->article_id;
        $comment->save();
        return $this->data(config('code.success'), '创建成功！');
    }

    /**
     * 删除评论
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        // 当前用户
        $user = $this->user();

        $comment = Comment::find($id);

        if (empty($comment)) {
            return $this->data(config('code.null'), '评论不存在');
        }

        if ($user->isAuthorOf($comment)) {
            $comment->delete();
            return $this->data(config('code.success'), '删除成功');
        }

        return $this->data(config('code.refuse_err'), '你无权删除');
    }

    /**
     * 是否点赞
     * @param $id
     * @return mixed
     */
    public function hasVoteThisComment($id)
    {
        // 当前用户
        $user = $this->user();

        if ($user->hasVotedThisComment($id)) {
            $data = [
                'voted' => true,
            ];
            return $this->data(config('code.success'), '已点赞', $data);
        }
        $data = [
            'voted' => false,
        ];
        return $this->data(config('code.success'), '没有点赞', $data);
    }
}
