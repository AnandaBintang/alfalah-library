<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'isbn',
        'publisher_id',
        'stock',
        'type',
        'is_student_work',
        'source',
        'catalog_code',
        'publication_year',
        'classification_code',
        'rack_location',
        'subject',
        'abstract',
        'cover_image_path',
    ];

    protected $casts = [
        'is_student_work' => 'boolean',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function files()
    {
        return $this->hasMany(BookFile::class);
    }

    public function bookStats()
    {
        return $this->hasMany(BookLoanStats::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
