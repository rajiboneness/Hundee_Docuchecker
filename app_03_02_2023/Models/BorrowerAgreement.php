<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BorrowerAgreement extends Model
{
    use HasFactory, SoftDeletes;

    public function borrowerDetails()
    {
        return $this->belongsTo('App\Models\Borrower', 'borrower_id', 'id');
    }

    public function agreementDetails()
    {
        return $this->belongsTo('App\Models\Agreement', 'agreement_id', 'id');
    }
}
