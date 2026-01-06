<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actual extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_code',
        'kpi_item',
        'kpi_unit',
        'review_period',
        'target',
        'actual',
        'kpi_percentage',
        'kpi_calculation',
        'supporting_document',
        'comment',
        'record_name',
        'record_file',
        'department_name',
        'kpi_weighting',
        'date',
        'semester',
        'detail',
        'trend',
        'status',
        'invalid_weight',
        'is_valid',
        'deadline',
        'input_by',
        'input_at',
        'asst_mng_checked_by',
        'asst_mng_checked_at',
        'checked_by',
        'checked_at',
        'approved_by',
        'approved_at',
        'mng_approved_by',
        'mng_approved_at',
        'employee_id',
    ];
}
