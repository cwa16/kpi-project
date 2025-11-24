<?php

namespace App\Http\Controllers;

use App\Models\Actual;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\DepartmentActual;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupportingDocumentController extends Controller
{
    public function index(Request $request)
    {
        $department = $request->query('department');

        if ($department) {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->where('employees.department_id', $department)
                ->select('employees.id', 'employees.name', 'employees.nik', 'employees.occupation', 'departments.name as department')
                ->get();
            $departments = DB::table('departments')
                ->select('id', 'name')
                ->get();
        } else {
            return view('supporting-documents.employee-list', [
                'title' => 'Lihat Data Pendukung',
                'desc' => 'Employee List',
            ]);
        }

        return view('supporting-documents.employee-list', [
            'title' => 'Lihat Data Pendukung',
            'desc' => 'Employee List',
            'employees' => $employees,
            'departments' => $departments,
        ]);
    }

    public function indexDept()
    {
        $departments = DB::table('departments')
            ->get();

        return view('supporting-documents.department-list', [
            'title' => 'Lihat Data Pendukung',
            'desc' => 'Department List',
            'departments' => $departments,
        ]);
    }

    public function employeeSupportingDocumentList(Request $request)
    {
        $employeeQuery = $request->query('employee');
        $yearQuery = $request->query('year');

        $employeeDetail = DB::table('employees')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('employees.id', $employeeQuery)
            ->select('employees.name', 'nik', 'occupation', 'departments.name as department')
            ->first();

        $targets = DB::table('targets')->leftJoin('target_units', 'targets.target_unit_id', '=', 'target_units.id')
            ->where('targets.employee_id', $employeeQuery)
            ->whereYear('targets.date', $yearQuery)
            ->select('targets.*', 'target_units.*')
            ->get();

        $actuals = DB::table('actuals')->leftJoin('employees', 'actuals.employee_id', '=', 'employees.id')
            ->where('employees.id', $employeeQuery)
            ->whereYear('actuals.date', $yearQuery)
            ->select('actuals.*', 'employees.name as employee_name', 'employees.nik as employee_nik', 'employees.id as employee_id')
            ->get();

        return view('supporting-documents.employee-supporting-document', [
            'title' => 'Lihat Data Pendukung',
            'desc' => 'Employee',
            'targets' => $targets,
            'actuals' => $actuals,
            'employee' => $employeeDetail,
        ]);
    }

    public function departmentSupportingDocumentList(Request $request)
    {
        $departmentQuery = $request->query('department');
        $yearQuery = $request->query('year');

        $departmentDetail = DB::table('departments')
            ->where('departments.id', $departmentQuery)
            ->select('departments.name')
            ->first();

        $targets = DB::table('department_targets')->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
            ->where('department_targets.department_id', $departmentQuery)
            ->whereYear('department_targets.date', $yearQuery)
            ->select('department_targets.*', 'target_units.*')
            ->get();

        $actuals = DB::table('department_actuals')->leftJoin('departments', 'department_actuals.department_id', '=', 'departments.id')
            ->where('department_actuals.department_id', $departmentQuery)
            ->whereYear('department_actuals.date', $yearQuery)
            ->select('department_actuals.*', 'departments.name as department_name', 'departments.code as department_code', 'departments.id as department_id')
            ->get();

        return view('supporting-documents.department-supporting-document', [
            'title' => 'Lihat Data Pendukung',
            'desc' => 'Department',
            'targets' => $targets,
            'actuals' => $actuals,
            'department' => $departmentDetail,
        ]);
    }

    public function showFile(Request $request)
    {
        $month = $request->query('month');
        $actualId = $request->query('actual_id');

        $pdfUrls = Actual::whereMonth('date', $month)
            ->where('id', $actualId)
            ->get(['id', 'record_file', 'kpi_code', 'kpi_item', 'status', 'comment'])
            ->toArray();

        return response()->json($pdfUrls);
    }
    public function showFileDept(Request $request)
    {
        $month = $request->query('month');
        $actualId = $request->query('actual_id');

        $pdfUrls = DepartmentActual::whereMonth('date', $month)
            ->where('id', $actualId)
            ->get(['id', 'record_file', 'kpi_code', 'kpi_item', 'status', 'comment'])
            ->toArray();

        return response()->json($pdfUrls);
    }

    public function indexMaster(Request $request)
    {
        $data = DB::table('master_data_pendukung')->get();

        return view('supporting-documents.master-supporting-document', [
            'title' => 'Master Data Pendukung',
            'desc' => 'Master Data Pendukung',
            'data' => $data,
        ]);
    }

    public function indexInputMaster(Request $request)
    {
        return view('supporting-documents.input-master-supporting-document', [
            'title' => 'Input Master Data Pendukung',
            'desc' => 'Input Master Data Pendukung',
        ]);
    }

    public function storeMaster(Request $request)
    {
        $request->validate([
            'no_kpi' => 'required|string',
            'nama_kpi' => 'required|string',
            'url_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = $request->file('url_file')->store('master_data_pendukung', 'public');

        DB::table('master_data_pendukung')->insert([
            'no_kpi' => $request->input('no_kpi'),
            'nama_kpi' => $request->input('nama_kpi'),
            'url_file' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('masterSupportingDocument')->with('success', 'Master data pendukung berhasil ditambahkan.');
    }

    public function showDocument($id)
    {
        $data = DB::table('master_data_pendukung')->where('id', $id)->first();
        $url = Storage::url($data->url_file);

        return redirect()->to($url);
    }

    public function editMaster($id)
    {
        $data = DB::table('master_data_pendukung')->where('id', $id)->first();

        return view('supporting-documents.edit-master-supporting-document', [
            'title' => 'Edit Master Data Pendukung',
            'desc' => 'Edit Master Data Pendukung',
            'data' => $data,
        ]);
    }

    public function updateMaster(Request $request, $id)
    {
        $request->validate([
            'no_kpi' => 'required|string',
            'nama_kpi' => 'required|string',
            'url_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $updateData = [
            'no_kpi' => $request->input('no_kpi'),
            'nama_kpi' => $request->input('nama_kpi'),
            'updated_at' => now(),
        ];

        if ($request->hasFile('url_file')) {
            $filePath = $request->file('url_file')->store('master_data_pendukung', 'public');
            $updateData['url_file'] = $filePath;
        }

        DB::table('master_data_pendukung')->where('id', $id)->update($updateData);

        return redirect()->route('masterSupportingDocument')->with('success', 'Master data pendukung berhasil diperbarui.');
    }

    public function destroyMaster($id)
    {
        DB::table('master_data_pendukung')->where('id', $id)->delete();

        return redirect()->route('masterSupportingDocument')->with('success', 'Master data pendukung berhasil dihapus.');
    }
}
