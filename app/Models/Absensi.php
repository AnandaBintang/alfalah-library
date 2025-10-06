<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
  /** @use HasFactory<\Database\Factories\AbsensiFactory> */
  use HasFactory, SoftDeletes;

  protected $fillable = ['user_id', 'absensi_tanggal', 'absensi_keterangan'];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
