<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Word;

class WordController extends Controller
{
    public function show($id)
    {
        $word = Word::find($id);
        return view('pages/word', compact('word'));
    }
}
