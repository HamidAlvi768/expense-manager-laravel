<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'expense_category_id',
        'amount',
        'description',
        'expense_date',
        'reason',
        'creation_mode',
        'status',
        'created_by',
        'updated_by'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with Account
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    // Relationship with ExpenseCategory
    public function expenseCategory()
    {
        return $this->belongsTo(DdExpenseCategory::class, 'expense_category_id');
    }

    // Relationship with Transaction (through morphMany)
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }
}
