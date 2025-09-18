<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'account_title', 
        'account_type_id', 
        'balance', 
        'total',
        'withdrawal',
        'deposit',
        'notes', 
        'status', 
        'created_by', 
        'updated_by'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountType()
    {
        return $this->belongsTo(DdAccountType::class,'account_type_id');
    }
}
