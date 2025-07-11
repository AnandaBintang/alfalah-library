<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'subtitle',
    'isbn',
    'publisher_id',
    'writer_id',
    'stock',
    'type',
    'is_student_work',
    'is_ebook',
    'ebook_type',
    'ebook_link',
    'ebook_file_path',
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
    'is_ebook' => 'boolean',
  ];

  public function publisher()
  {
    return $this->belongsTo(Publisher::class);
  }

  public function writer()
  {
    return $this->belongsTo(Writer::class);
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

  public function cartItem(): HasMany
  {
    return $this->hasMany(CartItem::class);
  }

  public function kondisi(): HasMany
  {
    return $this->hasMany(KondisiBook::class);
  }

  public function getEbookUrlAttribute(): ?string
  {
    if (!$this->is_ebook) {
      return null;
    }

    if ($this->ebook_type === 'link') {
      return $this->ebook_link;
    }

    if ($this->ebook_type === 'pdf' && $this->ebook_file_path) {
      return asset('storage/' . $this->ebook_file_path);
    }

    return null;
  }

  public function getIsEbookAvailableAttribute(): bool
  {
    return $this->is_ebook && (
      ($this->ebook_type === 'link' && !empty($this->ebook_link)) ||
      ($this->ebook_type === 'pdf' && !empty($this->ebook_file_path))
    );
  }
}
