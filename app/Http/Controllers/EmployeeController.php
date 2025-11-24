<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    public function index()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $employees = DB::table('employees')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select('employees.id', 'employees.nik', 'employees.name', 'employees.occupation', 'departments.name as department')
            ->get();

        $user = Auth::user();
        $role = $user->role;
        $email = $user->email;
        $departmentID = $user->department_id;

        $div1Dept = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C',])->get();
        $div1DeptManager = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Div 1'])->get();
        $div2Dept = DB::table('departments')->whereIn('name', ['Sub Div D', 'Sub Div E', 'Sub Div F',])->get();
        $div2DeptManager = DB::table('departments')->whereIn('name', ['Sub Div D', 'Sub Div E', 'Sub Div F', 'Div 2'])->get();
        $fad = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])->get();
        $accDept = DB::table('departments')->whereIn('name', ['Accounting', 'Finance'])->get();
        $ws = DB::table('departments')->where('name', '=', 'Workshop')->get();
        $fsd = DB::table('departments')->where('name', '=', 'FSD')->get();
        $factory = DB::table('departments')->where('name', '=', 'Factory')->get();
        $diva = DB::table('departments')->where('name', '=', 'Sub Div A')->get();
        $divb = DB::table('departments')->where('name', '=', 'Sub Div B')->get();
        $divc = DB::table('departments')->where('name', '=', 'Sub Div C')->get();
        $divd = DB::table('departments')->where('name', '=', 'Sub Div D')->get();
        $dive = DB::table('departments')->where('name', '=', 'Sub Div E')->get();
        $divf = DB::table('departments')->where('name', '=', 'Sub Div F')->get();
        $checker1 = DB::table('departments')->where('id', '=', $departmentID)->get();

        $allDept = Department::all();

        if ($role == 'Checker Div 1') {
            $deptList = $div1Dept;
        } else if ($role == 'Checker Div 2') {
            $deptList = $div2Dept;
        } else if ($role == 'Checker WS') {
            $deptList = $ws;
        } else if ($role == 'Checker Factory') {
            $deptList = $factory;
        } else if ($role == 'FAD') {
            $deptList = $fad;
        } elseif ($email == 'siswantoko@bskp.co.id') {
            $deptList = $div1DeptManager;
        } else if ($email == 'tabrani@bskp.co.id') {
            $deptList = $div2DeptManager;
        } else if ($email == 'hendi@bskp.co.id') {
            $deptList = $accDept;
        } else if ($role == 'Approver' || $role == 'Mng Approver') {
            $deptList = $allDept;
        } elseif ($role == 'Inputer' && $email == 'fsd@bskp.co.id') {
            $deptList = $fsd;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.a@bskp.co.id') {
            $deptList = $diva;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.b@bskp.co.id') {
            $deptList = $divb;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.c@bskp.co.id') {
            $deptList = $divc;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.d@bskp.co.id') {
            $deptList = $divd;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.e@bskp.co.id') {
            $deptList = $dive;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.f@bskp.co.id') {
            $deptList = $divf;
        } elseif ($role == 'Checker 1') {
            $deptList = $checker1;
        } else {
            $deptList = [];
        }

        // dd($deptList, $role, $email, $div2Dept);

        // if ($email == 'sub.divisi.a@bskp.co.id') {
        //     $deptList = $diva;
        // } else if ($email == 'sub.divisi.b@bskp.co.id') {
        //     $deptList = $divb;
        // } else if ($email == 'sub.divisi.c@bskp.co.id') {
        //     $deptList = $divc;
        // } else if ($email == 'sub.divisi.d@bskp.co.id') {
        //     $deptList = $divd;
        // } else if ($email == 'sub.divisi.e@bskp.co.id') {
        //     $deptList = $dive;
        // } else if ($email == 'sub.divisi.f@bskp.co.id') {
        //     $deptList = $divf;
        // } else {
        //     $deptList = [];
        // }

        // dashboard card
        $manager = Employee::where('status', '=', 'Manager')->count();
        $asstMng = Employee::where('status', '=', 'Staff')->count();
        $monthly = Employee::where('status', '=', 'Monthly')->count();

        $actualManager = DB::table('actuals')
            ->join('employees', 'actuals.employee_id', '=', 'employees.id')
            ->where('employees.status', '=', 'Manager')
            ->whereMonth('actuals.created_at', '=', value: $month)
            ->whereYear('actuals.created_at', '=', value: $year)
            ->count();

        $actualAsstManager = DB::table('actuals')
            ->join('employees', 'actuals.employee_id', '=', 'employees.id')
            ->where('employees.status', '=', 'Staff')
            ->whereMonth('actuals.created_at', '=', value: $month)
            ->whereYear('actuals.created_at', '=', value: $year)
            ->count();

        $actualMonthly = DB::table('actuals')
            ->join('employees', 'actuals.employee_id', '=', 'employees.id')
            ->where('employees.status', '=', 'Monthly')
            ->whereMonth('actuals.created_at', '=', value: $month)
            ->whereYear('actuals.created_at', '=', value: $year)
            ->count();

        // dd($actualMonthly, $actualAsstManager, $actualManager);

        $notification = DB::table('notifications')->orderBy('created_at', 'desc')
            ->first();


        // Approval request

        $gaMngDept = ['HR Legal', 'IT', 'QA/QM', 'GA', 'Safety', 'Enviro'];
        $accMngDept = ['Accounting', 'Finance'];
        $dirDept = ['SPID', 'FAD', 'FSD', 'Factory', 'Workshop', 'Security'];
        $div1MngDept = ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Div 1'];
        $div2MngDept = ['Sub Div D', 'Sub Div E', 'Sub Div F', 'Div 2'];
        $div1ClerkDept = ['Sub Div A', 'Sub Div B', 'Sub Div C'];
        $div2ClerkDept = ['Sub Div D', 'Sub Div E', 'Sub Div F'];
        $hrdDept = Department::all()->pluck('name')->toArray();

        // if ($email == 'johari@bskp.co.id') {


        // }

        if ($role == 'Approver') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $hrdDept)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $hrdDept)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $hrdDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $hrdDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            // dd($approveListGroup, $approveListDeptGroup);
        } elseif ($role == 'Checker 1' || $role == 'Checker Factory' || $role == 'Checker WS') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('asst_mng_checked_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->where('departments.id', '=', $departmentID)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('asst_mng_checked_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->where('departments.id', '=', $departmentID)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('asst_mng_checked_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->where('departments.id', '=', $departmentID)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('asst_mng_checked_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->where('departments.id', '=', $departmentID)
                ->groupBy('departments.name', 'departments.id')
                ->get();
            //
        } elseif ($role == 'Checker Div 1') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('checked_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div1ClerkDept)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('checked_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div1ClerkDept)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('checked_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div1ClerkDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('checked_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div1ClerkDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();
        } elseif ($role == 'Checker Div 2') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('checked_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div2ClerkDept)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('checked_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div2ClerkDept)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('checked_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div2ClerkDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('checked_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div2ClerkDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();
        } elseif ($email == 'johari@bskp.co.id') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $gaMngDept)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $gaMngDept)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $gaMngDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $gaMngDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();
        } elseif ($email == 'surya-sp@bskp.co.id') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $dirDept)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $dirDept)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $dirDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $dirDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();
        } elseif ($email == 'hendi@bskp.co.id') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $accMngDept)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $accMngDept)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $accMngDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $accMngDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();
        } elseif ($email == 'siswantoko@bskp.co.id') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div1MngDept)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div1MngDept)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div1MngDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div1MngDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();
        } elseif ($email == 'tabrani@bskp.co.id') {
            $approveList = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div2MngDept)
                ->count();

            $approveListDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div2MngDept)
                ->count();

            $approveListGroup = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div2MngDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();

            $approveListDeptGroup = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as total'), 'departments.id as department_id')
                ->where('mng_approved_by', '=', null)
                ->whereMonth('department_actuals.created_at', '=', $month)
                ->whereYear('department_actuals.created_at', '=', $year)
                ->whereIn('departments.name', $div2MngDept)
                ->groupBy('departments.name', 'departments.id')
                ->get();
        } else {
            $approveList = 0;
            $approveListDept = 0;
            $approveListGroup = [];
            $approveListDeptGroup = [];
        }

        // dd($approveListDept);


        // dd($approveList);




        return view('dashboard', [
            'title' => 'Dashboard',
            'desc' => 'Analytics',
            'employees' => $employees,
            'deptLists' => $deptList,
            'notification' => $notification,
            'manager' => $manager,
            'asstMng' => $asstMng,
            'monthly' => $monthly,
            'actualManager' => $actualManager,
            'actualAsstManager' => $actualAsstManager,
            'actualMonthly' => $actualMonthly,
            'approveList' => $approveList,
            'approveListDept' => $approveListDept,
            'approveListGroup' => $approveListGroup,
            'approveListDeptGroup' => $approveListDeptGroup,

        ]);
    }

    public function filter(Request $request)
    {
        $department = $request->query('department');
        $name = $request->query('name');
        $year = $request->query('year');
        $semester = $request->query('semester');
        $user = Auth::user();
        $departmentID = $user->department_id;

        $user = Auth::user();
        $role = $user->role;
        $email = $user->email;

        $div1Dept = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C'])->get();
        $div2Dept = DB::table('departments')->whereIn('name', ['Sub Div D', 'Sub Div E', 'Sub Div F'])->get();
        $ws = DB::table('departments')->where('name', '=', 'Workshop')->get();
        $fad = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])->get();
        $divMng = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'Div 1', 'Div 2'])->get();
        $accDept = DB::table('departments')->whereIn('name', ['Accounting', 'Finance'])->get();
        $fsd = DB::table('departments')->where('name', '=', 'FSD')->get();
        $factory = DB::table('departments')->where('name', '=', 'Factory')->get();
        $diva = DB::table('departments')->where('name', '=', 'Sub Div A')->get();
        $divb = DB::table('departments')->where('name', '=', 'Sub Div B')->get();
        $divc = DB::table('departments')->where('name', '=', 'Sub Div C')->get();
        $divd = DB::table('departments')->where('name', '=', 'Sub Div D')->get();
        $dive = DB::table('departments')->where('name', '=', 'Sub Div E')->get();
        $divf = DB::table('departments')->where('name', '=', 'Sub Div F')->get();
        $checker1 = DB::table('departments')->where('id', '=', $departmentID)->get();
        $allDept = Department::all();

        if ($role == 'Checker Div 1') {
            $deptList = $div1Dept;
        } else if ($role == 'Checker Div 2') {
            $deptList = $div2Dept;
        } else if ($role == 'Checker WS') {
            $deptList = $ws;
        } else if ($role == 'Checker Factory') {
            $deptList = $factory;
        } else if ($role == 'FAD') {
            $deptList = $fad;
        } elseif ($email == 'siswantoko@bskp.co.id' || $email == 'tabrani@bskp.co.id') {
            $deptList = $divMng;
        } else if ($email == 'hendi@bskp.co.id') {
            $deptList = $accDept;
        } else if ($role == 'Approver' || $role == 'Mng Approver') {
            $deptList = $allDept;
        } elseif ($role == 'Inputer' && $email == 'fsd@bskp.co.id') {
            $deptList = $fsd;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.a@bskp.co.id') {
            $deptList = $diva;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.b@bskp.co.id') {
            $deptList = $divb;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.c@bskp.co.id') {
            $deptList = $divc;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.d@bskp.co.id') {
            $deptList = $divd;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.e@bskp.co.id') {
            $deptList = $dive;
        } elseif ($role == 'Inputer' && $email == 'sub.divisi.f@bskp.co.id') {
            $deptList = $divf;
        } elseif ($role == 'Checker 1') {
            $deptList = $checker1;
        } else {
            $deptList = collect();
        }

        $deptNames = $deptList->pluck('name')->toArray();

        $department = $request->input('department');
        $name = $request->input('name');
        $year = $request->input('year');
        $semester = $request->input('semester');


        if ($department || $name || $year || $semester) {
            $query = DB::table('employees')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('actuals', 'employees.id', '=', 'actuals.employee_id')
                ->select('employees.id', 'employees.nik', 'employees.name', 'employees.occupation', 'departments.name as department', 'departments.id as department_id', DB::raw('MIN(actuals.date) as actual_date'))
                ->whereIn('departments.name', $deptNames);

            if ($department) {
                $query->where('departments.id', $department);
            }

            if ($name) {
                $query->where('employees.name', 'like', '%' . $name . '%');
            }

            if ($year) {
                $query->whereYear('actuals.date', $year);
            }

            if ($semester) {
                $query->where('actuals.semester', $semester);
            }

            $data = $query->groupBy('employees.id', 'employees.nik', 'employees.name', 'employees.occupation', 'departments.name', 'departments.id')->get();

            return response()->json($data);
        } else {
            // Return an empty response or a default message if no query parameters are provided
            return response()->json([]);
        }
    }

    public function indexMasterInput()
    {
        $departments = DB::table('departments')->get();
        $gaMngDept = DB::table('departments')->whereIn('name', ['HR Legal', 'IT', 'QA/QM', 'GA', 'Safety', 'Enviro'])->select('id', 'name')->get();
        $accMngDept = DB::table('departments')->whereIn('name', ['Accounting', 'Finance'])->select('id', 'name')->get();
        $dirDept = DB::table('departments')->whereIn('name', ['SPID', 'FAD', 'FSD', 'Factory', 'Workshop', 'Security'])->select('id', 'name')->get();
        $div1MngDept = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Div 1'])->select('id', 'name')->get();
        $div2MngDept = DB::table('departments')->whereIn('name', ['Sub Div D', 'Sub Div E', 'Sub Div F', 'Div 2'])->select('id', 'name')->get();
        $individuals = DB::table('departments')->whereIn('name', ['HR Legal', 'IT', 'QA/QM', 'GA', 'Safety', 'Enviro', 'BSKP', 'SPID', 'Accounting', 'FAD', 'Div 1', 'Div 2', 'Finance'])->select('name')->select('id', 'name')->get();
        $dirMng = DB::table('employees')->whereIn('occupation', ['Mng', 'Dir'])->select('department_id', 'name')->get();
        // dd($dirMng);

        // dd($individual);


        return view(
            'master-input',
            [
                'title' => 'Master Input',
                'desc' => 'Setting Master Input',
                'departments' => $departments,
                'gaMngDept' => $gaMngDept,
                'accMngDept' => $accMngDept,
                'dirDept' => $dirDept,
                'div1MngDept' => $div1MngDept,
                'div2MngDept' => $div2MngDept,
                'individuals' => $individuals,
                'dirMng' => $dirMng,

            ]
        );
    }

    public function updateMasterInput(Request $request)
    {
        $departmentID = $request->department_id;
        $departmentName = $request->department_name;
        $columnName = $request->column;
        $oldEmail = $request->old_email;
        $newEmail = $request->new_email;


        if ($departmentName == 'Sub Div A' || $departmentName == 'Sub Div B' || $departmentName == 'Sub Div C') {
            $newRole = 'Checker Div 1';
        } elseif ($departmentName == 'Sub Div D' || $departmentName == 'Sub Div E' || $departmentName == 'Sub Div F') {
            $newRole = 'Checker Div 2';
        } else {
            $newRole = $request->new_role;
        }



        // dd($departmentID, $columnName, $oldEmail, $newEmail, $newRole, $departmentName);

        $rowsAffectedDept = DB::table('departments')->where('id', '=', $departmentID)
            ->update([
                $columnName => $newEmail
            ]);

        $rowsAffectedOldEmployee = DB::table('employees')->where('email', '=', $oldEmail)
            ->update([
                'role' => ''
            ]);
        // dd($rowsAffectedOldEmployee);
        $rowsAffectedNewEmployee = DB::table('employees')->where('email', '=', $newEmail)->update([
            'role' => $newRole
        ]);


        if ($rowsAffectedDept > 0 && $rowsAffectedOldEmployee > 0) {
            return back()->with('success', 'Data updated succesfully');
        } else {
            return back()->with('error', 'Error Occured');
        }
    }
}
