<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldParent extends Model
{
    use HasFactory, SoftDeletes;

    public function childRelation() {
        return $this->hasMany('\App\Models\FieldParentRelation', 'parent_id', 'id')->orderBy('child_position');
    }
}
