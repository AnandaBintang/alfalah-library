<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KondisiBook extends Model
{
    /** @use HasFactory<\Database\Factories\KondisiBookFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['book_id', 'reported_at', 'status', 'notes'];

    public function book(): BelongsTo
    {
      return $this->belongsTo(Book::class);
    }
}
