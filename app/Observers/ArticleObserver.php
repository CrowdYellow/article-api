<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    public function saving(Article $article)
    {
        $article->excerpt = make_excerpt($article->body);
        $article->body = clean($article->body, 'user_topic_body');
    }

    public function created(Article $article)
    {
        $article->user->articlesCount();
        $article->category->articlesCount();
    }

    public function deleted(Article $article)
    {
        $article->user->articlesCount();
        $article->category->articlesCount();
//        DB::table('replies')->where('article_id', $article->id)->delete();
//        DB::table('article_votes')->where('article_id', $article->id)->delete();
//        DB::table('article_topic')->where('article_id', $article->id)->delete();
    }
}
