<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'amount',
        'category_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'description',
        'eventtype',
        'created_by'
    ];
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(
            $this->type === 'income' ? DdIncomeCategory::class : DdExpenseCategory::class,
            'category_id'
        );
    }
}


