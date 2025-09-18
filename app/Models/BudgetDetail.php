<?php

namespace App\Models;
use App\services\UserLogServices;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DdAccountType;

class BudgetDetail extends Model
{
    use  Loggable;
    protected $fillable = [
        'user_id',
        'category_id',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomeCategory()
    {
        return $this->belongsTo(DdIncomeCategory::class, 'category_id');
    }

}
