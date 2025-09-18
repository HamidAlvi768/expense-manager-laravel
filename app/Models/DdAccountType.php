<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class DdAccountType extends Model
{
    use HasFactory,Loggable;
    protected $table="dd_account_types";

    protected $fillable = [
        'id',
        'title',
        'status',
        'created_by',
        'updated_by',
    ];
}
