<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo('App\Models\Designation', 'designation_id', 'id');
    }

    public function office()
    {
        return $this->belongsTo('App\Models\Office', 'office_id', 'id');
    }
    public function manager() {
        return $this->belongsTo('App\Models\Employee', 'reporting_manager', 'id');
    }
}
