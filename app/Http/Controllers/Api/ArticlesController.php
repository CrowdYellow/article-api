<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ArticleRequest;
use App\Models\Article;
use App\Transformers\ArticleTransformer;

class ArticlesController extends Controller
{
    protected $articleTransformer;

    public function __construct(ArticleTransformer $articleTransformer)
    {
        $this->articleTransformer = $articleTransformer;
    }

    /**
     * 文章列表页
     * @return mixed
     */
    public function index()
    {
        $articles = Article::where('status', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $data     = [];

        foreach ($articles as $article) {
            $data[] = $this->articleTransformer->index($article);
        }

        return $this->data(config('code.success'), 'success', $data);
    }

    /**
     * 文章详情页
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $article = Article::find($id);

        $data = $this->articleTransformer->show($article);

        return $this->data(config('code.success'), 'success', $data);
    }

    /**
     * 添加文章
     * @param ArticleRequest $request
     * @return mixed
     */
    public function store(ArticleRequest $request)
    {
        // 当前用户
        $user = $this->user();

        $article              = new Article();
        $article->category_id = $request->category_id;
        $article->user_id     = $user->id;
        $article->title       = $request->title;
        $article->body        = $request->body;
        $article->save();

        return $this->data(config('code.success'), 'success', ['id' => $article->id]);
    }

    /**
     * 编辑文章
     * @param ArticleRequest $request
     * @param $id
     * @return mixed
     */
    public function update(ArticleRequest $request, $id)
    {
        // 当前用户
        $user = $this->user();

        $article = Article::find($id);

        if ($user->isAuthorOf($article)) {
            $article->category_id = $request->category_id;
            $article->title       = $request->title;
            $article->body        = $request->body;
            $article->save();
            return $this->data(config('code.success'), '修改成功', ['id' => $article->id]);
        }
        return $this->data(config('code.refuse_err'), '你无权修改');
    }

    /**
     * 删除文章
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        // 当前用户
        $user = $this->user();

        $article = Article::find($id);

        if ($user->isAuthorOf($article)) {
            $article->delete();
            return $this->data(config('code.success'), '删除成功');
        }

        return $this->data(config('code.refuse_err'), '你无权删除');
    }

    public function hasVotedThisArticle($id)
    {
        // 当前用户
        $user = $this->user();

        if ($user->hasVotedThisArticle($id)) {
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

    /**
     * 文章点赞
     * @param $id
     * @return mixed
     */
    public function vote($id)
    {
        // 当前用户
        $user = $this->user();
        // 当前文章
        $article = Article::find($id);

        $voted = $user->votedThisArticle($id);

        if (count($voted['attached']) > 0) {
            $article->increment('good_count');
            $data = [
                'voted' => true,
                'good_count' => $article->good_count
            ];
            return $this->data(config('code.success'), '成功点赞', $data);
        }
        $article->decrement('good_count');
        $data = [
            'voted' => false,
            'good_count' => $article->good_count
        ];
        return $this->data(config('code.success'), '取消点赞', $data);
    }
}
