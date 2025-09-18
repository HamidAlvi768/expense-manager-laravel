<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'file_path',
        'uploaded_at',
        'total_debits',
        'total_credits',
        'debit_amount',
        'credit_amount',
        'status',
        'created_by',
        'updated_by',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
