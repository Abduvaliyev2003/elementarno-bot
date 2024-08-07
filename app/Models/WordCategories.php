<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordCategories extends Model
{
    use HasFactory;

    protected $fillable  = [
        'title_uz',
        'title_ru',
        'title_en',
        'image',
    ];

    public function words()
    {
        return $this->hasMany(Word::class, 'category_id');
    }

}
