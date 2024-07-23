<?php

namespace App\Http\Controllers\WebApp;

use App\Models\Word;
use App\Models\WordCategories;

use Illuminate\Http\Client\Request;

class HomeController
{
    public function index()
    {
        $categories = WordCategories::with('words')->get();

        return view('pages/home', compact('categories'));
    }


    public function show($id)
    {
        $words = Word::where('category_id', $id)->get();
        $category = WordCategories::find($id);
        $categories = WordCategories::with('words')->get();
        return view('pages/word-lists', compact('words', 'category', 'categories'));
    }


}
