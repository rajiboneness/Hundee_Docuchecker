<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgreementDocument extends Model
{
    use HasFactory, SoftDeletes;
    public function DocumentDetails()
    {
        return $this->belongsTo('App\Models\Document', 'document_id', 'id');
    }

}