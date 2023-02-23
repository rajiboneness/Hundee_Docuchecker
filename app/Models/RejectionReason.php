<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectionReason extends Model
{
    use HasFactory;
    public function DocumentData()
    {
        return $this->belongsTo('App\Models\Document', 'document_parent_id', 'id');
    }
}
