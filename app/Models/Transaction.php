<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
		'reference_type',
		'reference_id',
        'transaction_type',
        'amount',
        'description',
        'related_transaction_id',
        'created_by',
        'updated_by',
    ];

    // Relationship with Account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Relationship with Related Transaction (for transfers)
    public function relatedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'related_transaction_id');
    }

	public function reference()
    {
        return $this->morphTo();
    }
}
