<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanExtension extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'loan_id',
        'previous_due_date',
        'new_due_date',
    ];

    protected $casts = [
        'previous_due_date' => 'date',
        'new_due_date' => 'date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
