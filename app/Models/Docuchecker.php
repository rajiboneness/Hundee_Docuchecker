<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docuchecker extends Model
{
    use HasFactory;
    public function CustomerData()
    {
        return $this->belongsTo('App\Models\Borrower', 'borrower_id', 'id');
    }
    public function agreementdata()
    {
        return $this->belongsTo('App\Models\Agreement', 'agreement_id', 'id');
    }
    public function RMData()
    {
        return $this->belongsTo('App\Models\Employee', 'rm_id', 'id');
    }
    public function OfficeData()
    {
        return $this->belongsTo('App\Models\Office', 'branch_id', 'id');
    }
}
