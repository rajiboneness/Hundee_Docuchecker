<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgreementDocumentUpload extends Model
{
    use HasFactory, SoftDeletes;

    public function documentDetails()
    {
        return $this->belongsTo('App\Models\AgreementDocument', 'agreement_document_id', 'id');
    }

    public function borrowerDetails()
    {
        return $this->belongsTo('App\Models\Borrower', 'borrower_id', 'id');
    }
}
