<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'user_id',
    'nis',
    'nisn',
    'class',
    'address',
    'phone',
    'gender',
    'library_card_image_path',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
