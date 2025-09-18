<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProvider extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'address',
        'rating',
        'discount_percentage',
        'co_percentage'
    ];

    public function insurance_providers(){
        return $this->hasMany(PatientDetail::class);
     }

}