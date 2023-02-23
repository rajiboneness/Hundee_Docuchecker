<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocuMail extends Model
{
    use HasFactory;
    public function CustomerData()
    {
        return $this->belongsTo('App\Models\Borrower', 'customer_id', 'id');
    }
    public function RMData()
    {
        return $this->belongsTo('App\Models\Employee', 'RM_id', 'id');
    }
    public function Docucheckers()
    {
        return $this->belongsTo('App\Models\Docuchecker', 'docuchecker_id', 'id');
    }
}
