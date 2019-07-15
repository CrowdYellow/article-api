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

    public function show($id)
    {
        $article = Article::find($id);

        $data = $this->articleTransformer->show($article);

        return $this->data(config('code.success'), 'success', $data);
    }

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
}
