<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Income extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'user_id', 
        'account_id', 
        'income_category_id',
        'amount',
        'description',
        'income_date',
        'notes',
        'creation_mode',
        'status', 
        'created_by', 
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class,'account_id');
    }
    public function incomeCategory()
    {
        return $this->belongsTo(DdIncomeCategory::class, 'income_category_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }
}

