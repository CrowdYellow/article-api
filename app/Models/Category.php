<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function articlesCount()
    {
        $this->article_count = $this->articles->count();
        $this->save();
    }
}
