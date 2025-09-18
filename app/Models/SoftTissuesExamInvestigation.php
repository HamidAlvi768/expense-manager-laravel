<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftTissuesExamInvestigation extends Model
{
    use HasFactory;

    protected $table="soft_tissues_exam_investigations";
  
    public function softtissue()
    {
        return $this->hasOne(ExtraOral::class, 'id', 'soft_tissue_id');
    }

    public function softtissuesexaminvestigations()
    {
        return $this->hasMany(SoftTissuesExamInvestigation::class, 'exam_investigation_id', 'id');
    }

}
