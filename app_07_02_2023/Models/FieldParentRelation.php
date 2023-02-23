<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldParentRelation extends Model
{
    use HasFactory, SoftDeletes;

    public function parentField() {
        return $this->belongsTo('\App\Models\FieldParent', 'parent_id', 'id');
    }

    public function childField() {
        return $this->belongsTo('\App\Models\Field', 'child_id', 'id');
    }
}
