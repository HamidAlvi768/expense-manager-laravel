<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Transfer extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'user_id',
        'from_account_id',
        'to_account_id',
        'transfer_amount',
        'notes',
        'description',
        'transfer_date',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with From Account
    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    // Relationship with To Account
    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }

}
