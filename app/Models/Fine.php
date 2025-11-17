<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fine extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'user_id',
    'loan_id',
    'amount',
    'description',
    'status',
    'paid_date',
  ];

  protected $casts = [
    'amount' => 'decimal:2',
    'paid_date' => 'date',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function book(): BelongsTo
  {
    return $this->belongsTo(Book::class);
  }

  public function loan()
  {
    return $this->belongsTo(Loan::class);
  }
}
