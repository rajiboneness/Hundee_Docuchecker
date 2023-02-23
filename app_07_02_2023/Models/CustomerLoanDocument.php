<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLoanDocument extends Model
{
    use HasFactory;
    public function documentData()
    {
        return $this->belongsTo('App\Models\Document', 'document_name', 'document_name');
    }
}
