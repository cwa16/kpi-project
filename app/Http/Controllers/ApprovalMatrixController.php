<?php
namespace App\Http\Controllers;

use App\Models\ApprovalMatrix;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalMatrixController extends Controller
{
    // Menampilkan halaman manajemen Approval Matrix
    public function index()
    {
        // 1. Ambil daftar KPI unik yang sudah diatur matriksnya (untuk Pagination)
        $kpis = ApprovalMatrix::select('kpi_name')
            ->distinct()
            ->orderBy('kpi_name')
            ->paginate(15);

        // 2. Ambil detail PIC khusus untuk KPI yang tampil di halaman ini (di-grouping)
        $matrices = ApprovalMatrix::with('employee')
            ->whereIn('kpi_name', $kpis->pluck('kpi_name'))
            ->get()
            ->groupBy('kpi_name');

        // 3. Dropdown Form Tambah
        $employees = Employee::where('is_active', 1)->orderBy('name')->get();
        $kpiNames  = DB::table('department_targets')
            ->select('indicator as name')
            ->distinct()
            ->orderBy('indicator')
            ->get();

        return view('approval_matrix.index', [
            'title'     => 'Approval Matrix',
            'desc'      => 'Manajemen PIC Approval per Nama KPI',
            'kpis'      => $kpis,
            'matrices'  => $matrices,
            'employees' => $employees,
            'kpiNames'  => $kpiNames,
        ]);
    }

    // Menyimpan data matriks baru
    public function store(Request $request)
    {
        $request->validate([
            'employee_nik'  => 'required',
            'kpi_name'      => 'required',
            'approval_type' => 'required',
        ]);

        // Cek apakah data sudah ada agar tidak duplikat
        $exists = ApprovalMatrix::where('employee_nik', $request->employee_nik)
            ->where('kpi_name', $request->kpi_name)
            ->where('approval_type', $request->approval_type)
            ->exists();

        if ($exists) {
            return back()->with('error', 'PIC ini sudah terdaftar untuk tipe approval tersebut di KPI ini!');
        }

        ApprovalMatrix::create([
            'employee_nik'  => $request->employee_nik,
            'kpi_name'      => $request->kpi_name,
            'approval_type' => $request->approval_type,
        ]);

        return back()->with('success', 'PIC Approval berhasil ditambahkan!');
    }

    // Menghapus data matriks
    public function destroy($id)
    {
        $matrix = ApprovalMatrix::findOrFail($id);
        $matrix->delete();

        return back()->with('success', 'Akses PIC berhasil dihapus!');
    }
}
