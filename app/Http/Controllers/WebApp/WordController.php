<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Word;
use App\Models\WordCategories;

class WordController extends Controller
{
    public function show($id)
    {
        $word = Word::find($id);
        $categories = WordCategories::get();
        return view('pages/word', compact('word', 'categories'));
    }
}
