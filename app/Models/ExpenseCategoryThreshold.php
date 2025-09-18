<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategoryThreshold extends Model
{
    use HasFactory;

    // Specify the table name if it's not plural of the model name
    protected $table = 'expense_category_thresholds';

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'expense_category_id',
        'threshold_amount',
        'is_important',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user who owns this threshold.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the expense category for this threshold.
     */
    public function expenseCategory()
    {
        return $this->belongsTo(DdExpenseCategory::class, 'expense_category_id');
    }
}
