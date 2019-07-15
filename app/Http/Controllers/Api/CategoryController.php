<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name', 'name')->get();

        return $this->data(config('code.success'), 'success', $categories);
    }
}
