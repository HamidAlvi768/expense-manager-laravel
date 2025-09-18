<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'expense_category_id',
        'amount',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function expenseCategory()
    {
        return $this->belongsTo(DdExpenseCategory::class, 'expense_category_id');
    }
}
