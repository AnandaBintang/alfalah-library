<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookFile extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'book_id',
        'file_url',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
