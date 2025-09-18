<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DdExpenseCategory extends Model
{
    use HasFactory;
    protected $table="dd_expense_categories";

    protected $fillable = [
        'id',
        'title',
        'status',
        'created_by',
        'updated_by',
    ];

    public function expenseItems(){
        return $this->hasMany(Expense::class, 'expense_category_id');
    }
}
