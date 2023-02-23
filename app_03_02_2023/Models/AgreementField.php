<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgreementField extends Model
{
    use HasFactory, SoftDeletes;

    public function fieldDetails()
    {
        return $this->belongsTo('App\Models\Field', 'field_id', 'id');
    }
}
