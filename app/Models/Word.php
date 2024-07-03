<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pronunciation',
        'translations', // JSON column
        'image',
        'category_id',
        'audio',
    ];

    protected $casts = [
        'translations' => 'array', // Cast translations to array
    ];

    public function category()
    {
        return $this->belongsTo(WordCategories::class, 'category_id');
    }


}
