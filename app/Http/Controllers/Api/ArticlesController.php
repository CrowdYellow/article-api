<?php

namespace App\Http\Controllers\Api;

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
}
