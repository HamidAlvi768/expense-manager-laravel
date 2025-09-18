<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraOralExamInvestigation extends Model
{
    use HasFactory;

    protected $table="extra_oral_exam_investigations";
  
    public function extraoral()
    {
        return $this->hasOne(ExtraOral::class, 'id', 'extra_oral_id');
    }

    public function extraoralexaminvestigations()
    {
        return $this->hasMany(ExtraOralExamInvestigation::class, 'exam_investigation_id', 'id');
    }

}
