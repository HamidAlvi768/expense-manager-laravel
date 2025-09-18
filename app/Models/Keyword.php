<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'category_id',
        'status',
        'created_by',
    ];

    // Relationship to categories
    public function category()
    {
        return $this->belongsTo(
            $this->type === 'income' ? DdIncomeCategory::class : DdExpenseCategory::class,
            'category_id'
        );
    }
}
