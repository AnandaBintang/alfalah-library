<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'quantity',
        'donation_date',
        'status',
        'image',
    ];

    protected $casts = [
        'donation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
