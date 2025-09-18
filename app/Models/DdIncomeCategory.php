<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class DdIncomeCategory extends Model
{
    use HasFactory,Loggable;
    protected $table="dd_income_categories";

    protected $fillable = [
        'id',
        'title',
        'status',
        'created_by',
        'updated_by',
    ];

    public function incomeItems(){
        return $this->hasMany(Income::class, 'income_category_id');
    }
}
