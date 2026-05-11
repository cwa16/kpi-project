<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalMatrix extends Model
{
    protected $table = 'approval_matrix';

    protected $fillable = [
        'employee_nik',
        'kpi_name',
        'approval_type',
    ];

    /**
     * Relasi ke tabel Employee berdasarkan NIK
     */
    public function employee()
    {
        // Parameter: (Nama Model Target, foreign_key di tabel approval_matrices, local_key di tabel employees)
        return $this->belongsTo(Employee::class, 'employee_nik', 'nik');
    }
}
