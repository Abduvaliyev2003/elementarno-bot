<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Word;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $words = Word::where('name', 'like', '%'.$query.'%')
                    ->orWhere('pronunciation', 'like', '%'.$query.'%')
                    ->orWhereJsonContains('translations->ru', $query) // Search within 'translations.ru'
                    ->orWhereJsonContains('translations->uz', $query) // Search within 'translations.uz'
                    ->get();

        return response()->json($words);
    }
}
