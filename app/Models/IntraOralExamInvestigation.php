<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntraOralExamInvestigation extends Model
{
    use HasFactory;

    protected $table="intra_oral_exam_investigations";
  
    public function extraoral()
    {
        return $this->hasOne(ExtraOral::class, 'id', 'intra_oral_id');
    }

    public function intraoralexaminvestigations()
    {
        return $this->hasMany(IntraOralExamInvestigation::class, 'exam_investigation_id', 'id');
    }

}
