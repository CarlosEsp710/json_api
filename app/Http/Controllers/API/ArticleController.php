<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceCollection;
use App\Http\Resources\ResourceObject;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index(): ResourceCollection
    {
        $articles =  Article::applyFilters()
            ->applySorts(request('sort'))
            ->jsonPaginate();

        return ResourceCollection::make($articles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data.attributes.title' => 'required',
            'data.attributes.slug' => 'required',
            'data.attributes.content' => 'required'
        ]);

        $article = Article::create([
            'title' => $request->input('data.attributes.title'),
            'slug' => $request->input('data.attributes.slug'),
            'content' => $request->input('data.attributes.content')
        ]);

        return ResourceObject::make($article);
    }

    public function show(Article $article): ResourceObject
    {
        return ResourceObject::make($article);
    }

    public function update(Request $request, Article $article)
    {
        //
    }

    public function destroy(Article $article)
    {
        //
    }
}
