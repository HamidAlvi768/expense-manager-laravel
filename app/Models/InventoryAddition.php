<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAddition extends Model
{
    protected $table = 'inventory_additions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'quantity',
        'inventory_id',
        'created_by', 
    ];
    public $timestamps = true;

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
