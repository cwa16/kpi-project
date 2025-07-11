<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Target;
use App\Models\Employee;
use Illuminate\View\View;
use App\Models\Department;
use App\Models\TargetUnit;
use Illuminate\Http\Request;
use App\Imports\TargetImport;
use Dflydev\DotAccessData\Data;
use App\Models\DepartmentTarget;
use App\Imports\TargetDeptImport;
use App\Imports\TargetUnitImport;
use App\Models\Actual;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\select;

class TargetController extends Controller
{
    public function department(Request $request)
    {
        $departmentID = $request->query('department');
        $employeeID = $request->query('employee');
        $status = $request->query('status');
        $user = Auth::user();
        $role = $user->role;
        $email = $user->email;
        $authDept = $user->department_id;

        $deptList = [];

        if ($role == 'Approver' || $email == 'johari@bskp.co.id' || $email == 'surya-sp@bskp.co.id') {
            $deptList = DB::table('departments')->get();
        } elseif ($email == 'siswantoko@bskp.co.id' || $email == 'tabrani@bskp.co.id' || $role == 'FAD') {
            $deptList = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'Div 1', 'Div 2'])->get();
        } elseif ($email == 'hendi@bskp.co.id') {
            $deptList = DB::table('departments')->whereIn('name', ['Accounting', 'Finance'])->get();
        } elseif ($role == 'Checker 1') {
            $deptList = DB::table('departments')->where('id', $authDept)->get();
        } elseif ($role == 'Checker 2') {
            $deptList = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'Div 1', 'Div 2'])->get();
        } elseif ($role == 'Checker WS' || $role == 'Checker Factory') {
            $deptList = DB::table('departments')->where('id', $authDept)->get();
        }


        $statusList = DB::table('employees')->select('status')->distinct()->get();

        // dd($deptList, $statusList);

        if ($departmentID == 'all' && $status) {
            if ($email == 'siswantoko@bskp.co.id' || $email == 'tabrani@bskp.co.id' || $role == 'FAD') {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.status', '=', $status)
                    ->where('employees.is_active', '=', 1)
                    ->whereIn('departments.name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            } elseif ($email == 'hendi@bskp.co.id') {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.status', '=', $status)
                    ->where('employees.is_active', '=', 1)
                    ->whereIn('departments.name', ['Accounting', 'Finance'])
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            } else {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.status', '=', $status)
                    ->where('employees.is_active', '=', 1)
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            }
        } elseif ($departmentID && $status) {
            if ($email == 'siswantoko@bskp.co.id' || $email == 'tabrani@bskp.co.id' || $role == 'FAD') {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.status', '=', $status)
                    ->where('employees.is_active', '=', 1)
                    ->where('departments.id', $departmentID)
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            } elseif ($email == 'hendi@bskp.co.id') {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.status', '=', $status)
                    ->where('departments.id', $departmentID)
                    ->where('employees.is_active', '=', 1)
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            } else {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.status', '=', $status)
                    ->where('departments.id', $departmentID)
                    ->where('employees.is_active', '=', 1)
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            }
        } elseif ($departmentID == 'all') {
            if ($email == 'siswantoko@bskp.co.id' || $email == 'tabrani@bskp.co.id' || $role == 'FAD') {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.is_active', '=', 1)
                    ->whereIn('departments.name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            } elseif ($email == 'hendi@bskp.co.id') {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.is_active', '=', 1)
                    ->whereIn('departments.name', ['Accounting', 'Finance'])
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            } else {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                    ->where('employees.is_active', '=', 1)
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            }

            // dd($departments);
        } elseif ($departmentID) {
            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                ->where('departments.id', $departmentID)
                ->where('employees.is_active', '=', 1)
                ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                ->orderBy('departments.id', 'asc')
                ->orderBy('employees.id', 'asc')
                ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
        } elseif ($status) {
            if ($email == 'siswantoko@bskp.co.id' || $email == 'tabrani@bskp.co.id' || $role == 'FAD') {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')->where('employees.status', '=', $status)
                    ->whereIn('departments.name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])
                    ->where('employees.is_active', '=', 1)
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            } elseif ($email == 'hendi@bskp.co.id') {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')->where('employees.status', '=', $status)
                    ->whereIn('departments.name', ['Accounting', 'Finance'])
                    ->where('employees.is_active', '=', 1)
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            } else {
                $departments = DB::table('departments')->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                    ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')->where('employees.status', '=', $status)
                    ->where('employees.is_active', '=', 1)
                    ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                    ->where('status', $status)
                    ->orderBy('departments.id', 'asc')
                    ->orderBy('employees.id', 'asc')
                    ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
            }
        } elseif ($employeeID) {
            $departments = DB::table('employees')->where('employees.id', $employeeID)
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('action_plans', 'action_plans.employee_id', '=', 'employees.id')
                ->where('employees.is_active', '=', 1)
                ->select('employees.id as employee_id', 'employees.name as employee', 'employees.nik as nik', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id', 'action_plans.file as file', 'action_plans.id as action_plan_id')
                ->orderBy('departments.id', 'asc')
                ->orderBy('employees.id', 'asc')
                ->paginate(10)->appends(['status' => $status, 'department' => $departmentID, 'employee' => $employeeID]);
        } else {
            return view('components/404-page');
        }

        $deadline = DB::table('setting_target_deadlines')->first();

        return view('target.input-target-department', ['title' => 'Input Target', 'desc' => 'Input KPI Target & Upload Program', 'departments' => $departments, 'deptList' => $deptList, 'statusList' => $statusList, 'deadline' => $deadline]);
    }
    public function deptList()
    {
        $user = Auth::user();
        $role = $user->role;
        $email = $user->email;

        $deptList = [];

        if ($role == 'Approver' || $email == 'johari@bskp.co.id' || $email == 'surya-sp@bskp.co.id') {
            $deptList = DB::table('departments')->leftJoin('dept_action_plans', 'departments.id', '=', 'dept_action_plans.department_id')->select('departments.*', 'dept_action_plans.file as file')->get();
        } elseif ($role == 'FAD' || $email == 'siswantoko@bskp.co.id' || $email == 'tabrani@bskp.co.id') {
            $deptList = DB::table('departments')->leftJoin('dept_action_plans', 'departments.id', '=', 'dept_action_plans.department_id')->select('departments.*', 'dept_action_plans.file as file')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])->get();
        } elseif ($email == 'hendi@bskp.co.id') {
            $deptList = DB::table('departments')->leftJoin('dept_action_plans', 'departments.id', '=', 'dept_action_plans.department_id')->select('departments.*', 'dept_action_plans.file as file')->whereIn('name', ['Accounting', 'Finance'])->get();
        } else {
            return back();
        }

        // dd($deptList);
        $deadline = DB::table('setting_target_deadlines')->first();

        return view('target.input-target-department-all', ['title' => 'Input Target', 'desc' => 'List Departemen', 'deptList' => $deptList, 'deadline' => $deadline]);
    }

    public function showDeptOne(Request $request)
    {
        $departmentID = $request->query('department');

        $departments = DB::table('departments')->leftJoin('dept_action_plans', 'departments.id', '=', 'dept_action_plans.department_id')->where('departments.id', '=', $departmentID)
            ->select('departments.*', 'dept_action_plans.id as action_plan_id', 'dept_action_plans.file as file')
            ->get();

        // dd($departments);
        $deadline = DB::table('setting_target_deadlines')->first();

        return view('target.input-target-department-one', ['title' => 'Input Target KPI', 'desc' => 'Department', 'departments' => $departments, 'deadline' => $deadline]);
    }

    public function show(Request $request)
    {
        $employeeID = $request->query('employee');
        $year = $request->query('year');
        $semester = $request->query('semester');
        $allStatus = $request->query('all');


        if ($employeeID && $year && $semester) {
            $employee = DB::table('employees')->leftJoin('departments', 'departments.id', 'employees.department_id')
                ->select('employees.id', 'employees.name', 'employees.nik', 'employees.occupation', 'departments.name as department')
                ->where('employees.id', '=', $employeeID)->first();

            $targets = DB::table('targets')->leftJoin('target_units', 'target_units.id', '=', 'targets.target_unit_id')
                ->select('target_units.*', 'targets.*', DB::raw('YEAR(targets.date) as year'))
                ->where('employee_id', $employeeID)
                ->where('targets.is_active', '=', true)
                ->where(DB::raw('YEAR(targets.date)'), $year)
                ->get();
            // dd($targets);

            $inactiveTargets = DB::table('targets')->leftJoin('target_units', 'target_units.id', '=', 'targets.target_unit_id')
                ->select('target_units.*', 'targets.*', DB::raw('YEAR(targets.date) as year'))
                ->where('employee_id', $employeeID)
                ->where('targets.is_active', '=', false)
                ->where(DB::raw('YEAR(targets.date)'), $year)
                ->get();

            // dd($inactiveTargets);
            $deadline = DB::table('setting_target_deadlines')->first();

            return view('target.input-target-kpi', ['title' => 'Input KPI Target', 'desc' => 'Employees', 'employee' => $employee, 'targets' => $targets, 'all' => $allStatus, 'inactiveTargets' => $inactiveTargets, 'deadline' => $deadline]);
        } else {

            return view('components/404-page');
        }
    }

    public function showDept(Request $request)
    {
        $departmentID = $request->query('department');
        $year = $request->query('year');
        $semester = $request->query('semester');

        if ($departmentID && $year && $semester) {
            $dept = DB::table('departments')->where('departments.id', '=', $departmentID)
                ->first();

            if (!$dept) {
                return view('components/404-page');
            }

            $targetDept = DB::table('department_targets')->leftJoin('departments', 'departments.id', '=', 'department_targets.department_id')
                ->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
                ->select('department_targets.id', 'department_targets.department_id', 'department_targets.target_unit_id', 'department_targets.trend', 'departments.name as department', 'department_targets.code', 'department_targets.indicator', 'department_targets.calculation', 'department_targets.supporting_document', 'department_targets.period', 'department_targets.weighting', 'department_targets.unit', 'target_units.target_1', 'target_units.target_2', 'target_units.target_3', 'target_units.target_4', 'target_units.target_5', 'target_units.target_6', 'target_units.target_7', 'target_units.target_8', 'target_units.target_9', 'target_units.target_10', 'target_units.target_11', 'target_units.target_12', 'department_targets.id as department_target_id', 'department_targets.is_active')
                ->where('departments.id', '=', $departmentID)
                ->where('department_targets.is_active', '=', true)
                ->where(DB::raw('YEAR(department_targets.date)'), $year)
                ->get();

            $inactiveTargets = DB::table('department_targets')->leftJoin('departments', 'departments.id', '=', 'department_targets.department_id')
                ->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
                ->select('department_targets.id', 'department_targets.department_id', 'department_targets.target_unit_id', 'department_targets.trend', 'departments.name as department', 'department_targets.code', 'department_targets.indicator', 'department_targets.calculation', 'department_targets.supporting_document', 'department_targets.period', 'department_targets.weighting', 'department_targets.unit', 'target_units.target_1', 'target_units.target_2', 'target_units.target_3', 'target_units.target_4', 'target_units.target_5', 'target_units.target_6', 'target_units.target_7', 'target_units.target_8', 'target_units.target_9', 'target_units.target_10', 'target_units.target_11', 'target_units.target_12', 'department_targets.id as department_target_id')
                ->where('departments.id', '=', $departmentID)
                ->where('department_targets.is_active', '=', false)
                ->where(DB::raw('YEAR(department_targets.date)'), $year)
                ->get();


            // dd($targetDept);
            $deadline = DB::table('setting_target_deadlines')->first();

            return view('target.input-target-kpi-department', ['title' => 'Input KPI Target', 'desc' => 'Department', 'departments' => $dept, 'targets' => $targetDept, 'inactiveTargets' => $inactiveTargets, 'deadline' => $deadline]);
        } else {

            return view('components/404-page');
        }
    }


    public function showImport()
    {
        return view('target.import-target-kpi-employee', ['title' => 'Import Target', 'desc' => 'Import Target Employee ']);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            flash()->error('Import target only accept .xlsx format');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Import the data without saving the file
        Excel::import(new TargetImport($this), $request->file('file'));

        return back();
    }

    public function storeTarget(array $row, $targetUnitId, $year)
    {
        $employee = DB::table('employees')->where('nik', $row['nik'])->value('id');
        // dd($employee);
        $yearToInsert = $year;


        $searchConditions = [
            'date' => $yearToInsert,
            'employee_id' => $employee,
            'code' => $row['kode_kpi']
        ];

        $weighting = isset($row['bobot']) ? ((float)$row['bobot'] * 100) . '%' : '0%';

        $data = [
            'code' => $row['kode_kpi'],
            'indicator' => $row['kpi'],
            'calculation' => $row['cara_menghitung'],
            'supporting_document' => $row['data_pendukung_harus_di_isi'],
            'trend' => $row['trend'],
            'period' => $row['periode_review'],
            'unit' => $row['unit'],
            'weighting' => $weighting,
            'detail' => $row['keterangan'],
            'target_unit_id' => $targetUnitId,
        ];

        // dd($data);

        Target::updateOrCreate($searchConditions, $data);
    }

    public function showImportDept()
    {
        return view('target.import-target-kpi-department', ['title' => 'Import Target', 'desc' => 'Import Target Department']);
    }

    public function importDept(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            flash()->error('Import target only accept .xlsx format');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Import the data without saving the file
        Excel::import(new TargetDeptImport($this), $request->file('file'));

        return back();
    }

    public function storeTargetDept(array $row, $targetUnitId, $year, $department_id)
    {
        // dd($row);
        $department = DB::table('departments')->where('id', '=', $department_id)->value('id');
        // dd($department);
        $yearToInsert = $year;

        $weighting = isset($row['bobot']) ? ((float)$row['bobot'] * 100) . '%' : '0%';

        $searchConditions = [
            'date' => $yearToInsert,
            'department_id' => $department,
            'code' => $row['kode_kpi']
        ];

        $data = [
            // 'code' => $row['kode_kpi'],
            'indicator' => $row['kpi'],
            'calculation' => $row['cara_menghitung'],
            'supporting_document' => $row['data_pendukung_harus_di_isi'],
            'trend' => $row['trend'],
            'period' => $row['periode_review'],
            'unit' => $row['unit'],
            'weighting' => $weighting,
            'detail' => $row['penjelasan'],
            'target_unit_id' => $targetUnitId,
        ];

        // dd($data);

        DepartmentTarget::updateOrCreate($searchConditions, $data);
        // dd($response);
    }

    public function edit($id)
    {
        $employeeID = request()->query('employee');
        $employeeDetail = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->select('employees.*', 'departments.name as department')
            ->where('employees.id', $employeeID)
            ->first();
        $target = DB::table('targets')->leftJoin('target_units', 'target_units.id', '=', 'targets.target_unit_id')
            ->select('target_units.*', 'targets.*', DB::raw('YEAR(targets.date) as year'), 'targets.id as target_id', 'target_units.id as target_unit_id')
            ->where('targets.id', $id)
            ->first();

        return view('target.edit-target-kpi', ['title' => 'Edit Data Target', 'desc' => 'Edit Target Employee', 'target' => $target, 'employeeDetail' => $employeeDetail]);
    }
    public function editDept($id)
    {
        $departmentID = request()->query('department');
        $departmentDetail = DB::table('departments')->where('id', $departmentID)->first();
        $target = DB::table('department_targets')->leftJoin('target_units', 'target_units.id', '=', 'department_targets.target_unit_id')
            ->select('target_units.*', 'department_targets.*', DB::raw('YEAR(department_targets.date) as year'), 'department_targets.id as department_target_id', 'target_units.id as target_unit_id')
            ->where('department_targets.id', $id)
            ->first();

        return view('target.edit-target-kpi-department', ['title' => 'Edit Data Target', 'desc' => 'Edit Target Department', 'target' => $target, 'departmentDetail' => $departmentDetail]);
    }

    public function update(Request $request)
    {

        $id = $request->target_id;
        $year = $request->year;
        $semester = $request->semester;
        $employee_id = $request->employee_id;
        $targetUnitId = $request->target_unit_id;

        $target = Target::find($id);
        $target->update(request()->all());
        DB::table('actuals')
            ->where('kpi_code', $request->code)
            ->where('employee_id', $employee_id)
            ->whereYear('date', $year)
            ->update(['kpi_unit' => $request->unit]);
        $targetUnit = TargetUnit::find($targetUnitId);
        $targetUnit->update(request()->all());

        return redirect()->to('/target/input-target-kpi?employee=' . $employee_id . '&year=' . $year . '&semester=' . $semester)->with('success', 'Data updated successfully.');
    }

    public function updateDept(Request $request)
    {
        $id = $request->department_target_id;
        $year = $request->year;
        $semester = $request->semester;
        $departmentID = $request->department_id;
        $targetUnitId = $request->target_unit_id;

        $target = DepartmentTarget::find($id);
        $target->update(request()->all());
        $targetUnit = TargetUnit::find($targetUnitId);
        $targetUnit->update(request()->all());
        DB::table('department_actuals')
            ->where('kpi_code', $request->code)
            ->where('department_id', $departmentID)
            ->whereYear('date', $year)
            ->update(['kpi_unit' => $request->unit]);

        return redirect()->to('/target/input-target-kpi-department?department=' . $departmentID . '&year=' . $year  . '&semester=' . $semester)->with('success', 'Data updated successfully.');
    }

    public function settingTargetDeadline()
    {
        $targetDeadline = DB::table('setting_target_deadlines')->first();
        if ($targetDeadline) {
            $startDate = Carbon::parse($targetDeadline->start_date)->format('d-m-Y');
            $endDate = Carbon::parse($targetDeadline->end_date)->format('d-m-Y');
        } else {
            $startDate = null;
            $endDate = null;
        }
        return view('target.setting-target-deadline', [
            'title' => 'Setting Deadline',
            'desc' => 'Target',
            'targetDeadline' => $targetDeadline,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function updateTargetDeadline(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate the input dates
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            flash()->error('Invalid date format or end date is before start date.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('setting_target_deadlines')->updateOrInsert(
            ['id' => 1],
            [
                'start_date' => Carbon::parse($startDate)->format('Y-m-d H:i:s'),
                'end_date' => Carbon::parse($endDate)->format('Y-m-d H:i:s'),
            ]
        );

        return redirect()->back()->with('success', 'Target deadline updated successfully.');
    }

    public function createTarget(Request $request)
    {
        $employeeID = $request->query('employee');

        $employeeDetail = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->select('employees.*', 'departments.name as department')->where('employees.id', $employeeID)->first();
        return view('target.create-target-kpi', [
            'title' => 'Create Target',
            'desc' => 'Employee',
            'employeeID' => $employeeID,
            'employeeDetail' => $employeeDetail,
        ]);
    }
    public function createTargetDept(Request $request)
    {
        $departmentID = $request->query('department');
        $departmentDetail = DB::table('departments')->where('id', $departmentID)->first();
        return view('target.create-target-kpi-department', [
            'title' => 'Create Target',
            'desc' => 'Department',
            'departmentID' => $departmentID,
            'departmentDetail' => $departmentDetail,
        ]);
    }

    public function storeTargetKpi(Request $request)
    {
        // dd($request->all());
        $employeeID = $request->employee_id;
        $semester = $request->semester;
        $year = $request->year;
        $yearToInsert = Carbon::parse($year)->format('Y-m-d H:i:s');

        TargetUnit::create([
            'target_1' => $request->target_1,
            'target_2' => $request->target_2,
            'target_3' => $request->target_3,
            'target_4' => $request->target_4,
            'target_5' => $request->target_5,
            'target_6' => $request->target_6,
            'target_7' => $request->target_7,
            'target_8' => $request->target_8,
            'target_9' => $request->target_9,
            'target_10' => $request->target_10,
            'target_11' => $request->target_11,
            'target_12' => $request->target_12,
        ]);

        $targetUnitId = DB::table('target_units')->latest()->first()->id;

        Target::create([
            'code' => $request->code,
            'indicator' => $request->indicator,
            'calculation' => $request->calculation,
            'period' => $request->period,
            'unit' => $request->unit,
            'supporting_document' => $request->supporting_document,
            'trend' => $request->trend,
            'weighting' => $request->weighting,
            'detail' => $request->detail,
            'employee_id' => $employeeID,
            'target_unit_id' => $targetUnitId,
            'date' => $yearToInsert,
            'semester' => $semester,
            'is_active' => true,
        ]);

        return redirect()->to("/target/input-target-kpi?employee=$employeeID&semester=$semester&year=$year")->with('success', 'Target created successfully.');
    }

    public function storeTargetKpiDept(Request $request)
    {
        // dd($request->all());
        $departmentID = $request->department_id;
        $semester = $request->semester;
        $year = $request->year;
        $yearToInsert = Carbon::parse($year)->format('Y-m-d H:i:s');

        TargetUnit::create([
            'target_1' => $request->target_1,
            'target_2' => $request->target_2,
            'target_3' => $request->target_3,
            'target_4' => $request->target_4,
            'target_5' => $request->target_5,
            'target_6' => $request->target_6,
            'target_7' => $request->target_7,
            'target_8' => $request->target_8,
            'target_9' => $request->target_9,
            'target_10' => $request->target_10,
            'target_11' => $request->target_11,
            'target_12' => $request->target_12,
        ]);

        $targetUnitId = DB::table('target_units')->latest()->first()->id;

        DepartmentTarget::create([
            'code' => $request->code,
            'indicator' => $request->indicator,
            'calculation' => $request->calculation,
            'period' => $request->period,
            'unit' => $request->unit,
            'supporting_document' => $request->supporting_document,
            'trend' => $request->trend,
            'weighting' => $request->weighting,
            'detail' => $request->detail,
            'department_id' => $departmentID,
            'target_unit_id' => $targetUnitId,
            'date' => $yearToInsert,
            'semester' => $semester,
            'is_active' => true,
        ]);

        return redirect()->to("/target/input-target-kpi-department?department=$departmentID&semester=$semester&year=$year")->with('success', 'Target created successfully.');
    }
}
