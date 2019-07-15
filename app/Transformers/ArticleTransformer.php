<?php


namespace App\Transformers;

use App\Models\Article;

class ArticleTransformer
{
    public function index(Article $article)
    {
        return [
            'id'            => $article->id,
            'title'         => $article->title,
            'excerpt'       => $article->excerpt,
            'comment_count' => $article->comment_count,
            'good_count'    => $article->good_count,
            'view_count'    => $article->view_count,
            'name'          => $article->user->name,
            'avatar'        => env('APP_URL') . $article->user->avatar,
            'created_at'    => (string)$article->created_at,
        ];
    }

    public function show(Article $article)
    {
        return [
            'id'                 => $article->id,
            'title'              => $article->title,
            'body'               => $article->body,
            'comment_count'      => $article->comment_count,
            'good_count'         => $article->good_count,
            'view_count'         => $article->view_count,
            'is_allowed_comment' => $article->is_allowed_comment,
            'name'               => $article->user->name,
            'avatar'             => env('APP_URL') . $article->user->avatar,
            'created_at'         => (string)$article->created_at,
        ];
    }
}