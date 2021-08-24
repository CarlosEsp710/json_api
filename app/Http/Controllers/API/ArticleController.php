<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index(): ArticleCollection
    {
        $articles =  Article::applyFilters()
            ->applySorts(request('sort'))
            ->jsonPaginate();

        return ArticleCollection::make($articles);
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

        return ArticleResource::make($article);
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
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
