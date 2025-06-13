<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // $currentMonth = Carbon::now()->month;
        $semester = $request->query('semester');
        $year = $request->query('year');

        if ($semester === '1') {
            $months = range(1, 6); // January to June
        } else {
            $months = range(7, 12); // July to December
        }

        $departments = Department::all();

        $targetCounts = DB::table('targets')
            ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->where(DB::raw('YEAR(targets.date)'), $year)
            ->select('departments.code as code', DB::raw('count(targets.id) as total'))
            ->groupBy('departments.code')
            ->get();

        $targetCountsDept = DB::table('department_targets')
            ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
            ->where(DB::raw('YEAR(department_targets.date)'), $year)
            ->select('departments.code as code', DB::raw('count(department_targets.id) as total'))
            ->groupBy('departments.code')
            ->get();


        $targetUnitCounts1 = DB::table('target_units')
            ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
            ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->whereYear('targets.date', '=', $year)
            // ->whereNotNull('target_units.id')
            ->select(
                'departments.id as department_id',
                DB::raw('count(target_units.target_1) as total_1'),
                DB::raw('count(target_units.target_2) as total_2'),
                DB::raw('count(target_units.target_3) as total_3'),
                DB::raw('count(target_units.target_4) as total_4'),
                DB::raw('count(target_units.target_5) as total_5'),
                DB::raw('count(target_units.target_6) as total_6'),
            )
            ->groupBy('departments.id')
            ->get();


        $targetUnitCounts2 = DB::table('target_units')
            ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
            ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->whereYear('targets.date', '=', $year)
            ->select(
                'departments.id as department_id',
                DB::raw('count(target_units.target_7) as total_7'),
                DB::raw('count(target_units.target_8) as total_8'),
                DB::raw('count(target_units.target_9) as total_9'),
                DB::raw('count(target_units.target_10) as total_10'),
                DB::raw('count(target_units.target_11) as total_11'),
                DB::raw('count(target_units.target_12) as total_12')
            )
            ->groupBy('departments.id')
            ->get();

        $targetUnitCountsDept1 = DB::table('target_units')
            ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
            ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')

            ->whereYear('department_targets.date', '=', $year)
            // ->whereNotNull('target_units.id')
            ->select(
                'departments.id as department_id',
                DB::raw('count(target_units.target_1) as total_1'),
                DB::raw('count(target_units.target_2) as total_2'),
                DB::raw('count(target_units.target_3) as total_3'),
                DB::raw('count(target_units.target_4) as total_4'),
                DB::raw('count(target_units.target_5) as total_5'),
                DB::raw('count(target_units.target_6) as total_6'),
            )
            ->groupBy('departments.id')
            ->get();

        $targetUnitCountsDept2 = DB::table('target_units')
            ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
            ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
            ->whereYear('department_targets.date', '=', $year)
            ->select(
                'departments.id as department_id',
                DB::raw('count(target_units.target_7) as total_7'),
                DB::raw('count(target_units.target_8) as total_8'),
                DB::raw('count(target_units.target_9) as total_9'),
                DB::raw('count(target_units.target_10) as total_10'),
                DB::raw('count(target_units.target_11) as total_11'),
                DB::raw('count(target_units.target_12) as total_12')
            )
            ->groupBy('departments.id')
            ->get();


        // dd($targetUnitCounts1, $targetUnitCountsDept1, $targetUnitCounts2, $targetUnitCountsDept2);

        $actualCounts = DB::table('actuals')
            ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->select('departments.code as department_code', DB::raw('MONTH(actuals.date) as month'), DB::raw('count(actuals.status) as total'))
            ->where('actuals.status', 'Approved')
            ->where(DB::raw('YEAR(actuals.date)'), $year)
            ->whereIn(DB::raw('MONTH(actuals.date)'), $months)
            ->groupBy('departments.code', DB::raw('MONTH(actuals.date)'))
            ->get();

        $actualCountsDept = DB::table('department_actuals')
            ->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
            ->select('departments.code as department_code', DB::raw('MONTH(department_actuals.date) as month'), DB::raw('count(department_actuals.status) as total'))
            ->where('department_actuals.status', 'Approved')
            ->where(DB::raw('YEAR(department_actuals.date)'), $year)
            ->whereIn(DB::raw('MONTH(department_actuals.date)'), $months)
            ->groupBy('departments.code', DB::raw('MONTH(department_actuals.date)'))
            ->get();

        // dd($actualCounts, $actualCountsDept);


        return view('logs/log-check', [
            'title' => 'Log Check',
            'desc' => 'History',
            'departments' => $departments,
            'months' => $months,
            'targetCounts' => $targetCounts,
            'targetCountsDept' => $targetCountsDept,
            'actualCounts' => $actualCounts,
            'actualCountsDept' => $actualCountsDept,
            'targetUnitCounts1' => $targetUnitCounts1,
            'targetUnitCounts2' => $targetUnitCounts2,
            'targetUnitCountsDept1' => $targetUnitCountsDept1,
            'targetUnitCountsDept2' => $targetUnitCountsDept2,


        ]);
    }

    public function indexInput(Request $request)
    {

        $department = $request->query('department');
        $month = $request->query('month');
        $year = $request->query('year');
        $user = Auth::user();
        $role = $user->role;
        $authDept = Auth::user()->department_id;
        $departmentNames = [];
        if ($role == 'Checker Div 1' || $role == 'Checker Div 2') {
            $titlePage = 'Log Check';
        } else {
            $titlePage = 'Log Input';
        }

        if ($role == 'Checker Div 1') {
            $allDept = DB::table('departments')->whereIn('name', ['Div 1', 'Sub Div A', 'Sub Div B', 'Sub Div C'])->get();
            if ($allDept) {
                $deptList = ['Div 1', 'Sub Div A', 'Sub Div B', 'Sub Div C'];
            }
            $departmentNames = ['Sub Div A', 'Sub Div B', 'Sub Div C'];
        } elseif ($role == 'Checker Div 2') {
            $allDept = DB::table('departments')->whereIn('name', ['Div 2', 'Sub Div D', 'Sub Div E', 'Sub Div F'])->get();
            if ($allDept) {
                $deptList = ['Sub Div D', 'Sub Div E', 'Sub Div F'];
            }
            $departmentNames = ['Sub Div D', 'Sub Div E', 'Sub Div F'];
        } elseif ($role == 'Approver' || ($role == 'Mng Approver' && $department == 'All Dept')) {
            $allDept = Department::all();
            $departmentName = DB::table('departments')->pluck('name')->toArray();
            if ($departmentName) {
                $departmentNames = $departmentName;
                $deptList = $departmentName;
            }
        } elseif ($role == 'Approver' || $role == 'Mng Approver') {
            $allDept = Department::all();
            $departmentName = DB::table('departments')->where('id', '=', $department)->value('name');
            if ($departmentName) {
                $departmentNames = [$departmentName];
                $deptList = $departmentName;
            }
        } elseif ($role == 'Inputer') {
            $allDept = DB::table('departments')->where('id', $authDept)->get();
            $departmentName = DB::table('departments')->where('id', '=', $authDept)->value('name');
            if ($departmentName) {
                $departmentNames = [$departmentName];
                $deptList = [$departmentName];
            }
            // dd($deptList);
        } else {
            $allDept = DB::table('departments')->where('id', '=', $department)->get();
            if ($allDept) {
                $deptList = [$department];
            }
            $departmentName = DB::table('departments')->where('id', $department)->value('name');
            if ($departmentName) {
                $departmentNames = [$departmentName];
            }
        }

        // dd($deptList);
        if ($department == 'All Dept' && $month && $year) {
            $actualFilledCheck = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('actuals.status', '=', 'Filled')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->get();
            $actualCheckedCheck = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')

                ->where('actuals.status', '=', 'Checked 2')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->get();

            $actualApproved = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')

                ->where('actuals.status', '=', 'Approved')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->orderBy('actuals.approved_at', 'desc')->get();

            $actualChecked = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')

                ->where('actuals.status', '=', 'Checked 2')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->orderBy('actuals.checked_at', 'desc')->get();

            $actualFilled = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')

                ->whereIn('departments.name', $deptList)
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('departments.id as department_id', 'actuals.*')
                ->orderBy('actuals.approved_at', 'desc')->get();

            $actualFilledCount = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')

                ->where('actuals.input_at', '!=', '')
                ->where('actuals.record_file', '!=', '')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select(DB::raw('count(actuals.id) as total_filled'), 'departments.id as department_id')
                ->orderBy('actuals.input_at', 'desc')
                ->groupBy('departments.id')
                ->get();

            $actualFilledCountDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->where('department_actuals.input_at', '!=', '')
                ->where('department_actuals.record_file', '!=', '')
                ->whereMonth('department_actuals.date', $month)
                ->whereYear('department_actuals.date', $year)
                ->select(DB::raw('count(department_actuals.id) as total_filled'), 'departments.id as department_id')
                ->orderBy('department_actuals.input_at', 'desc')
                ->groupBy('departments.id')
                ->get();

            // dd($actualFilledCount, $actualFilledCountDept);

            $targetUnitFilledCountAll = DB::table('target_units')
                ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->where('targets.is_active', '=', 1)
                ->whereIn('departments.name', $departmentNames)
                ->whereYear('targets.date', $year)
                ->select(
                    'departments.id as department_id',
                    DB::raw('count(target_units.target_1) as total_1'),
                    DB::raw('count(target_units.target_2) as total_2'),
                    DB::raw('count(target_units.target_3) as total_3'),
                    DB::raw('count(target_units.target_4) as total_4'),
                    DB::raw('count(target_units.target_5) as total_5'),
                    DB::raw('count(target_units.target_6) as total_6'),
                    DB::raw('count(target_units.target_7) as total_7'),
                    DB::raw('count(target_units.target_8) as total_8'),
                    DB::raw('count(target_units.target_9) as total_9'),
                    DB::raw('count(target_units.target_10) as total_10'),
                    DB::raw('count(target_units.target_11) as total_11'),
                    DB::raw('count(target_units.target_12) as total_12'),
                )
                ->groupBy('departments.id')
                ->get();

            $targetUnitFilledCountAllDept = DB::table('target_units')
                ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                ->where('department_targets.is_active', '=', 1)
                ->whereIn('departments.name', $departmentNames)
                ->whereYear('department_targets.date', $year)
                ->select(
                    'departments.id as department_id',
                    DB::raw('count(target_units.target_1) as total_1'),
                    DB::raw('count(target_units.target_2) as total_2'),
                    DB::raw('count(target_units.target_3) as total_3'),
                    DB::raw('count(target_units.target_4) as total_4'),
                    DB::raw('count(target_units.target_5) as total_5'),
                    DB::raw('count(target_units.target_6) as total_6'),
                    DB::raw('count(target_units.target_7) as total_7'),
                    DB::raw('count(target_units.target_8) as total_8'),
                    DB::raw('count(target_units.target_9) as total_9'),
                    DB::raw('count(target_units.target_10) as total_10'),
                    DB::raw('count(target_units.target_11) as total_11'),
                    DB::raw('count(target_units.target_12) as total_12'),
                )
                ->groupBy('departments.id')
                ->get();

            if (!empty($departmentNames)) {
                $actualCheckedCount = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('actuals.checked_at', '!=', '')
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->count();

                $actualCheckedCountDept = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('department_actuals.checked_at', '!=', '')
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->count();

                $actualCheckedCountGroup = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('actuals.checked_at', '=', null)
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();

                $actualCheckedCountDeptGroup = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('department_actuals.checked_at', '=', null)
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(department_actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();
                // dd($actualCheckedCountGroup, $actualCheckedCountDeptGroup);

                $targetUnitCountAll = DB::table('target_units')
                    ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                    ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                    ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('targets.is_active', '=', 1)
                    ->whereYear('targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->get();

                $targetUnitCountAllDept = DB::table('target_units')
                    ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                    ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                    ->where('department_targets.is_active', '=', 1)
                    ->whereIn('departments.name', $departmentNames)
                    ->whereYear('department_targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->get();
                // dd($departmentNames, $actualCheckedCount, $actualCheckedCountDept, $targetUnitCountAll, $targetUnitCountAllDept, $actualFilledCount, $actualFilledCountDept);

                $totals = [
                    'total_1' => 0,
                    'total_2' => 0,
                    'total_3' => 0,
                    'total_4' => 0,
                    'total_5' => 0,
                    'total_6' => 0,
                    'total_7' => 0,
                    'total_8' => 0,
                    'total_9' => 0,
                    'total_10' => 0,
                    'total_11' => 0,
                    'total_12' => 0,
                ];

                // Sum the values for each target column
                foreach ($targetUnitCountAll as $item) {
                    $totals['total_1'] += $item->total_1;
                    $totals['total_2'] += $item->total_2;
                    $totals['total_3'] += $item->total_3;
                    $totals['total_4'] += $item->total_4;
                    $totals['total_5'] += $item->total_5;
                    $totals['total_6'] += $item->total_6;
                    $totals['total_7'] += $item->total_7;
                    $totals['total_8'] += $item->total_8;
                    $totals['total_9'] += $item->total_9;
                    $totals['total_10'] += $item->total_10;
                    $totals['total_11'] += $item->total_11;
                    $totals['total_12'] += $item->total_12;
                }

                foreach ($targetUnitCountAllDept as $item) {
                    $totals['total_1'] += $item->total_1;
                    $totals['total_2'] += $item->total_2;
                    $totals['total_3'] += $item->total_3;
                    $totals['total_4'] += $item->total_4;
                    $totals['total_5'] += $item->total_5;
                    $totals['total_6'] += $item->total_6;
                    $totals['total_7'] += $item->total_7;
                    $totals['total_8'] += $item->total_8;
                    $totals['total_9'] += $item->total_9;
                    $totals['total_10'] += $item->total_10;
                    $totals['total_11'] += $item->total_11;
                    $totals['total_12'] += $item->total_12;
                }
            } elseif ($role == 'Checker WS' || $role == 'Checker Factory') {
                $actualCheckedCount = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->where('departments.id', '=', $department)
                    ->where('actuals.checked_at', '!=', '')
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->select(DB::raw('count(actuals.id) as total_checked'), 'departments.id as department_id')
                    ->orderBy('actuals.input_at', 'desc')
                    ->groupBy('departments.id')
                    ->first();

                $actualCheckedCountDept = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->where('departments.id', '=', $department)
                    ->where('department_actuals.checked_at', '!=', '')
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->select(DB::raw('count(department_actuals.id) as total_checked'), 'departments.id as department_id')
                    ->orderBy('department_actuals.input_at', 'desc')
                    ->groupBy('departments.id')
                    ->first();

                $actualCheckedCountGroup = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('actuals.checked_at', '!=', '')
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();

                $actualCheckedCountDeptGroup = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('department_actuals.checked_at', '!=', '')
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(department_actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();
                // dd($actualCheckedCountGroup, $actualCheckedCountDeptGroup);

                $targetUnitCountAll = DB::table('target_units')
                    ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                    ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                    ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                    ->where('targets.is_active', '=', 1)
                    ->where('departments.id', '=', $department)
                    ->whereYear('targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->first();

                $targetUnitCountAllDept = DB::table('target_units')
                    ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                    ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                    ->where('department_targets.is_active', '=', 1)
                    ->where('departments.id', '=', $department)
                    ->whereYear('department_targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->first();
            }


            $departments = DB::table('departments')->get();
            $countEmployees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->select(DB::raw('count(employees.id) as total_employee'), 'departments.name as department_name', 'departments.id as department_id')->whereIn('departments.name', $deptList)->groupBy(['departments.name', 'departments.id'])->get();

            $acc = $actualCheckedCount;
            $accd = $actualCheckedCountDept;
            // dd($actualFilled);
            // dd($acc, $accd);
            // $tgUnitAll = $targetUnitCountAll;
            // $tgUnitAllDept = $targetUnitCountAllDept;
            $totalTgUnitAll = $totals;
            // dd($actualFilledCount, $actualFilledCountDept->tosSql());
            // dd($totals['total_1']);

            // dd($actualFilledCheck, $actualCheckedCheck, $actualApproved, $actualChecked, $countEmployees);
            return view('logs/log-input', [
                'title' => 'Log Input',
                'desc' => 'History',
                'actualFilledCheck' => $actualFilledCheck,
                'actualCheckedCheck' => $actualCheckedCheck,
                'actualApproved' => $actualApproved,
                'actualFilled' => $actualFilled,
                'actualChecked' => $actualChecked,
                'departments' => $departments,
                'countEmployees' => $countEmployees,
                'actualFilledCount' => $actualFilledCount,
                'actualFilledCountDept' => $actualFilledCountDept,
                'actualCheckedCount' => $acc,
                'actualCheckedCountDept' => $accd,
                'totalTgUnitAll' => $totalTgUnitAll,
                'targetUnitCountAll' => $targetUnitFilledCountAll,
                'targetUnitCountAllDept' => $targetUnitFilledCountAllDept,
                'allDept' => $allDept,
                'titlePage' => $titlePage,
                'actualCheckedCountGroup' => $actualCheckedCountGroup,
                'actualCheckedCountDeptGroup' => $actualCheckedCountDeptGroup,
            ]);
        } elseif ($department && $month && $year) {
            $actualFilledCheck = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $department)
                ->where('actuals.status', '=', 'Filled')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->get();
            $actualCheckedCheck = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $department)
                ->where('actuals.status', '=', 'Checked 2')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->get();

            $actualApproved = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $department)
                ->where('actuals.status', '=', 'Approved')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->orderBy('actuals.approved_at', 'desc')->get();

            $actualChecked = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $department)
                ->where('actuals.status', '=', 'Checked 2')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->orderBy('actuals.checked_at', 'desc')->get();

            $actualFilled = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $department)
                ->whereIn('actuals.status', ['Filled', 'Checked 1', 'Checked 2', 'Mng Approved', 'Approved'])
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('departments.id as department_id', 'actuals.*')
                ->orderBy('actuals.approved_at', 'desc')->get();

            // dd($actualFilled);

            $actualFilledCount = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $department)
                ->where('actuals.input_at', '!=', '')
                ->where('actuals.record_file', '!=', value: '')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select(DB::raw('count(actuals.id) as total_filled'), 'departments.id as department_id')
                ->orderBy('actuals.input_at', 'desc')
                ->groupBy('departments.id')
                ->first();

            $actualFilledCountDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->where('departments.id', $department)
                ->where('department_actuals.input_at', '!=', '')
                ->where('department_actuals.record_file', '!=', '')
                ->whereMonth('department_actuals.date', $month)
                ->whereYear('department_actuals.date', $year)
                ->select(DB::raw('count(department_actuals.id) as total_filled'), 'departments.id as department_id')
                ->orderBy('department_actuals.input_at', 'desc')
                ->groupBy('departments.id')
                ->first();

            // dd($actualFilledCount, $actualFilledCountDept);


            $targetUnitFilledCountAll = DB::table('target_units')
                ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->where('targets.is_active', '=', 1)
                ->whereIn('departments.name', $departmentNames)
                ->whereYear('targets.date', $year)
                ->select(
                    'departments.id as department_id',
                    DB::raw('count(target_units.target_1) as total_1'),
                    DB::raw('count(target_units.target_2) as total_2'),
                    DB::raw('count(target_units.target_3) as total_3'),
                    DB::raw('count(target_units.target_4) as total_4'),
                    DB::raw('count(target_units.target_5) as total_5'),
                    DB::raw('count(target_units.target_6) as total_6'),
                    DB::raw('count(target_units.target_7) as total_7'),
                    DB::raw('count(target_units.target_8) as total_8'),
                    DB::raw('count(target_units.target_9) as total_9'),
                    DB::raw('count(target_units.target_10) as total_10'),
                    DB::raw('count(target_units.target_11) as total_11'),
                    DB::raw('count(target_units.target_12) as total_12'),
                )
                ->groupBy('departments.id')
                ->first();

            $targetUnitFilledCountAllDept = DB::table('target_units')
                ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                ->where('department_targets.is_active', '=', 1)
                ->whereIn('departments.name', $departmentNames)
                ->whereYear('department_targets.date', $year)
                ->select(
                    'departments.id as department_id',
                    DB::raw('count(target_units.target_1) as total_1'),
                    DB::raw('count(target_units.target_2) as total_2'),
                    DB::raw('count(target_units.target_3) as total_3'),
                    DB::raw('count(target_units.target_4) as total_4'),
                    DB::raw('count(target_units.target_5) as total_5'),
                    DB::raw('count(target_units.target_6) as total_6'),
                    DB::raw('count(target_units.target_7) as total_7'),
                    DB::raw('count(target_units.target_8) as total_8'),
                    DB::raw('count(target_units.target_9) as total_9'),
                    DB::raw('count(target_units.target_10) as total_10'),
                    DB::raw('count(target_units.target_11) as total_11'),
                    DB::raw('count(target_units.target_12) as total_12'),
                )
                ->groupBy('departments.id')
                ->first();

            if (!empty($departmentNames)) {
                $actualCheckedCount = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('actuals.checked_at', '!=', '')
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->count();

                $actualCheckedCountDept = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('department_actuals.checked_at', '!=', '')
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->count();
                // dd($actualCheckedCount, $actualCheckedCountDept);

                $actualCheckedCountGroup = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('actuals.checked_at', '=', null)
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();

                $actualCheckedCountDeptGroup = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('department_actuals.checked_at', '=', null)
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(department_actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();
                // dd($actualCheckedCountGroup, $actualCheckedCountDeptGroup);

                $targetUnitCountAll = DB::table('target_units')
                    ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                    ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                    ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                    ->where('targets.is_active', '=', 1)
                    ->whereIn('departments.name', $departmentNames)
                    ->whereYear('targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->get();

                $targetUnitCountAllDept = DB::table('target_units')
                    ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                    ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                    ->where('department_targets.is_active', '=', 1)
                    ->whereIn('departments.name', $departmentNames)
                    ->whereYear('department_targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->get();



                // dd($departmentNames, $actualCheckedCount, $actualCheckedCountDept, $targetUnitCountAll, $targetUnitCountAllDept, $actualFilledCount, $actualFilledCountDept);

                $totals = [
                    'total_1' => 0,
                    'total_2' => 0,
                    'total_3' => 0,
                    'total_4' => 0,
                    'total_5' => 0,
                    'total_6' => 0,
                    'total_7' => 0,
                    'total_8' => 0,
                    'total_9' => 0,
                    'total_10' => 0,
                    'total_11' => 0,
                    'total_12' => 0,
                ];

                // Sum the values for each target column
                foreach ($targetUnitCountAll as $item) {
                    $totals['total_1'] += $item->total_1;
                    $totals['total_2'] += $item->total_2;
                    $totals['total_3'] += $item->total_3;
                    $totals['total_4'] += $item->total_4;
                    $totals['total_5'] += $item->total_5;
                    $totals['total_6'] += $item->total_6;
                    $totals['total_7'] += $item->total_7;
                    $totals['total_8'] += $item->total_8;
                    $totals['total_9'] += $item->total_9;
                    $totals['total_10'] += $item->total_10;
                    $totals['total_11'] += $item->total_11;
                    $totals['total_12'] += $item->total_12;
                }

                foreach ($targetUnitCountAllDept as $item) {
                    $totals['total_1'] += $item->total_1;
                    $totals['total_2'] += $item->total_2;
                    $totals['total_3'] += $item->total_3;
                    $totals['total_4'] += $item->total_4;
                    $totals['total_5'] += $item->total_5;
                    $totals['total_6'] += $item->total_6;
                    $totals['total_7'] += $item->total_7;
                    $totals['total_8'] += $item->total_8;
                    $totals['total_9'] += $item->total_9;
                    $totals['total_10'] += $item->total_10;
                    $totals['total_11'] += $item->total_11;
                    $totals['total_12'] += $item->total_12;
                }
            } elseif ($role == 'Checker WS' || $role == 'Checker Factory') {
                $actualCheckedCount = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->where('departments.id', '=', $department)
                    ->where('actuals.checked_at', '!=', '')
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->select(DB::raw('count(actuals.id) as total_checked'), 'departments.id as department_id')
                    ->orderBy('actuals.input_at', 'desc')
                    ->groupBy('departments.id')
                    ->first();

                $actualCheckedCountDept = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->where('departments.id', '=', $department)
                    ->where('department_actuals.checked_at', '!=', '')
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->select(DB::raw('count(department_actuals.id) as total_checked'), 'departments.id as department_id')
                    ->orderBy('department_actuals.input_at', 'desc')
                    ->groupBy('departments.id')
                    ->first();

                $actualCheckedCountGroup = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('actuals.checked_at', '=', null)
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();

                $actualCheckedCountDeptGroup = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('department_actuals.checked_at', '=', null)
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(department_actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();
                // dd($actualCheckedCountGroup, $actualCheckedCountDeptGroup);

                $targetUnitCountAll = DB::table('target_units')
                    ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                    ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                    ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                    ->where('targets.is_active', '=', 1)
                    ->where('departments.id', '=', $department)
                    ->whereYear('targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->first();

                $targetUnitCountAllDept = DB::table('target_units')
                    ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                    ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                    ->where('department_targets.is_active', '=', 1)
                    ->where('departments.id', '=', $department)
                    ->whereYear('department_targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->first();
            }


            $departments = DB::table('departments')->where('departments.id', '=', $department)->get();
            $countEmployees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->select(DB::raw('count(employees.id) as total_employee'), 'departments.name as department_name', 'departments.id as department_id')->whereIn('departments.name', $deptList)->groupBy(['departments.name', 'departments.id'])->get();
            // dd($deptList, $countEmployees);


            $acc = $actualCheckedCount ?? 0;
            $accd = $actualCheckedCountDept ?? 0;
            // dd($actualFilled);
            // dd($acc, $accd);
            // $tgUnitAll = $targetUnitCountAll;
            // $tgUnitAllDept = $targetUnitCountAllDept;
            $totalTgUnitAll = $totals;
            // dd($actualFilledCount, $actualFilledCountDept->tosSql());
            // dd($totals['total_1']);

            // dd($departmentNames, $totalTgUnitAll);
            // dd($countEmployees);

            // dd($actualFilledCheck, $actualCheckedCheck, $actualApproved, $actualChecked, $countEmployees);
            return view('logs/log-input', [
                'title' => 'Log Input',
                'desc' => 'History',
                'actualFilledCheck' => $actualFilledCheck,
                'actualCheckedCheck' => $actualCheckedCheck,
                'actualApproved' => $actualApproved,
                'actualFilled' => $actualFilled,
                'actualChecked' => $actualChecked,
                'departments' => $departments,
                'countEmployees' => $countEmployees,
                'actualFilledCount' => $actualFilledCount,
                'actualFilledCountDept' => $actualFilledCountDept,
                'actualCheckedCount' => $acc,
                'actualCheckedCountDept' => $accd,
                'totalTgUnitAll' => $totalTgUnitAll,
                'targetUnitCountAll' => $targetUnitFilledCountAll,
                'targetUnitCountAllDept' => $targetUnitFilledCountAllDept,
                'allDept' => $allDept,
                'titlePage' => $titlePage,
                'actualCheckedCountGroup' => $actualCheckedCountGroup,
                'actualCheckedCountDeptGroup' => $actualCheckedCountDeptGroup,
            ]);
        } elseif ($month && $year) {

            $actualFilledCheck = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $authDept)
                ->where('actuals.status', '=', 'Filled')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->get();
            $actualCheckedCheck = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $authDept)
                ->where('actuals.status', '=', 'Checked 2')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->get();

            $actualApproved = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $authDept)
                ->where('actuals.status', '=', 'Approved')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->orderBy('actuals.approved_at', 'desc')->get();

            $actualChecked = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $authDept)
                ->where('actuals.status', '=', 'Checked 2')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('actuals.id', 'actuals.kpi_code', 'actuals.kpi_item', 'actuals.kpi_unit', 'actuals.review_period', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.kpi_calculation', 'actuals.supporting_document', 'actuals.comment', 'actuals.record_file', 'actuals.department_name', 'actuals.kpi_weighting', 'actuals.date', 'actuals.semester', 'actuals.trend', 'actuals.status', 'actuals.detail', 'actuals.input_by', 'actuals.input_at', 'actuals.checked_by', 'actuals.checked_at', 'actuals.approved_by', 'actuals.approved_at', 'actuals.employee_id', 'actuals.created_at', 'actuals.updated_at', 'departments.id as department_id')
                ->orderBy('actuals.checked_at', 'desc')->get();

            $actualFilled = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->whereIn('departments.name', $deptList)
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select('departments.id as department_id', 'actuals.*')
                ->orderBy('actuals.approved_at', 'desc')->get();

            $actualFilledCount = DB::table('actuals')
                ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('departments.id', $authDept)
                ->where('actuals.input_at', '!=', '')
                ->where('actuals.record_file', '!=', '')
                ->whereMonth('actuals.date', $month)
                ->whereYear('actuals.date', $year)
                ->select(DB::raw('count(actuals.id) as total_filled'), 'departments.id as department_id', 'kpi_code')
                ->orderBy('actuals.input_at', 'desc')
                ->groupBy('departments.id')
                ->first();

            $actualFilledCountDept = DB::table('department_actuals')
                ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->where('departments.id', $authDept)
                ->where('department_actuals.input_at', '!=', '')
                ->where('department_actuals.record_file', '!=', '')
                ->whereMonth('department_actuals.date', $month)
                ->whereYear('department_actuals.date', $year)
                ->select(DB::raw('count(department_actuals.id) as total_filled'), 'departments.id as department_id', 'kpi_code')
                ->orderBy('department_actuals.input_at', 'desc')
                ->groupBy('departments.id')
                ->first();

            // $actualFilledCountGroup = DB::table('actuals')
            //     ->join('employees', 'actuals.employee_id', '=', 'employees.id')
            //     ->join('departments', 'employees.department_id', '=', 'departments.id')
            //     ->where('departments.id', $authDept)
            //     ->where('actuals.input_at', '=', null)
            //     ->where('actuals.record_file', '!=', '')
            //     ->whereMonth('actuals.date', $month)
            //     ->whereYear('actuals.date', $year)
            //     ->select(DB::raw('count(actuals.id) as total_filled'), 'departments.id as department_id', 'kpi_code')
            //     ->orderBy('actuals.input_at', 'desc')
            //     ->groupBy('departments.id')
            //     ->get();

            // $actualFilledCountDeptGroup = DB::table('department_actuals')
            //     ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
            //     ->where('departments.id', $authDept)
            //     ->where('department_actuals.input_at', '=', null)
            //     ->where('department_actuals.record_file', '!=', '')
            //     ->whereMonth('department_actuals.date', $month)
            //     ->whereYear('department_actuals.date', $year)
            //     ->select(DB::raw('count(department_actuals.id) as total_filled'), 'departments.id as department_id', 'kpi_code')
            //     ->orderBy('department_actuals.input_at', 'desc')
            //     ->groupBy('departments.id')
            //     ->get();

            // dd($actualFilledCountGroup, $actualFilledCountDeptGroup);


            $targetUnitFilledCountAll = DB::table('target_units')
                ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->where('targets.is_active', '=', 1)
                ->whereIn('departments.name', $departmentNames)
                ->whereYear('targets.date', $year)
                ->select(
                    'departments.id as department_id',
                    DB::raw('count(target_units.target_1) as total_1'),
                    DB::raw('count(target_units.target_2) as total_2'),
                    DB::raw('count(target_units.target_3) as total_3'),
                    DB::raw('count(target_units.target_4) as total_4'),
                    DB::raw('count(target_units.target_5) as total_5'),
                    DB::raw('count(target_units.target_6) as total_6'),
                    DB::raw('count(target_units.target_7) as total_7'),
                    DB::raw('count(target_units.target_8) as total_8'),
                    DB::raw('count(target_units.target_9) as total_9'),
                    DB::raw('count(target_units.target_10) as total_10'),
                    DB::raw('count(target_units.target_11) as total_11'),
                    DB::raw('count(target_units.target_12) as total_12'),
                )
                ->groupBy('departments.id')
                ->first();

            $targetUnitFilledCountAllDept = DB::table('target_units')
                ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                ->where('department_targets.is_active', '=', 1)
                ->whereIn('departments.name', $departmentNames)
                ->whereYear('department_targets.date', $year)
                ->select(
                    'departments.id as department_id',
                    DB::raw('count(target_units.target_1) as total_1'),
                    DB::raw('count(target_units.target_2) as total_2'),
                    DB::raw('count(target_units.target_3) as total_3'),
                    DB::raw('count(target_units.target_4) as total_4'),
                    DB::raw('count(target_units.target_5) as total_5'),
                    DB::raw('count(target_units.target_6) as total_6'),
                    DB::raw('count(target_units.target_7) as total_7'),
                    DB::raw('count(target_units.target_8) as total_8'),
                    DB::raw('count(target_units.target_9) as total_9'),
                    DB::raw('count(target_units.target_10) as total_10'),
                    DB::raw('count(target_units.target_11) as total_11'),
                    DB::raw('count(target_units.target_12) as total_12'),
                )
                ->groupBy('departments.id')
                ->first();

            if (!empty($departmentNames)) {
                $actualCheckedCount = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('actuals.checked_at', '!=', null)
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->count();

                $actualCheckedCountDept = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('department_actuals.checked_at', '!=', null)
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->count();
                // dd($actualCheckedCount, $actualCheckedCountDept, $departmentNames);

                $actualCheckedCountGroup = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('actuals.checked_at', '=', null)
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();

                $actualCheckedCountDeptGroup = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->whereIn('departments.name', $departmentNames)
                    ->where('department_actuals.checked_at', '=', null)
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->select('departments.name as department_name', DB::raw('count(department_actuals.id) as total_not_checked'), 'kpi_code')
                    ->groupBy('departments.name',)
                    ->get();
                // dd($actualCheckedCountGroup, $actualCheckedCountDeptGroup);

                $targetUnitCountAll = DB::table('target_units')
                    ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                    ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                    ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                    ->where('targets.is_active', '=', 1)
                    ->whereIn('departments.name', $departmentNames)
                    ->whereYear('targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->get();

                $targetUnitCountAllDept = DB::table('target_units')
                    ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                    ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                    ->where('department_targets.is_active', '=', 1)
                    ->whereIn('departments.name', $departmentNames)
                    ->whereYear('department_targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->get();
                // dd($departmentNames, $actualCheckedCount, $actualCheckedCountDept, $targetUnitCountAll, $targetUnitCountAllDept, $actualFilledCount, $actualFilledCountDept);

                $totals = [
                    'total_1' => 0,
                    'total_2' => 0,
                    'total_3' => 0,
                    'total_4' => 0,
                    'total_5' => 0,
                    'total_6' => 0,
                    'total_7' => 0,
                    'total_8' => 0,
                    'total_9' => 0,
                    'total_10' => 0,
                    'total_11' => 0,
                    'total_12' => 0,
                ];

                // Sum the values for each target column
                foreach ($targetUnitCountAll as $item) {
                    $totals['total_1'] += $item->total_1;
                    $totals['total_2'] += $item->total_2;
                    $totals['total_3'] += $item->total_3;
                    $totals['total_4'] += $item->total_4;
                    $totals['total_5'] += $item->total_5;
                    $totals['total_6'] += $item->total_6;
                    $totals['total_7'] += $item->total_7;
                    $totals['total_8'] += $item->total_8;
                    $totals['total_9'] += $item->total_9;
                    $totals['total_10'] += $item->total_10;
                    $totals['total_11'] += $item->total_11;
                    $totals['total_12'] += $item->total_12;
                }

                foreach ($targetUnitCountAllDept as $item) {
                    $totals['total_1'] += $item->total_1;
                    $totals['total_2'] += $item->total_2;
                    $totals['total_3'] += $item->total_3;
                    $totals['total_4'] += $item->total_4;
                    $totals['total_5'] += $item->total_5;
                    $totals['total_6'] += $item->total_6;
                    $totals['total_7'] += $item->total_7;
                    $totals['total_8'] += $item->total_8;
                    $totals['total_9'] += $item->total_9;
                    $totals['total_10'] += $item->total_10;
                    $totals['total_11'] += $item->total_11;
                    $totals['total_12'] += $item->total_12;
                }
            } elseif ($role == 'Checker WS' || $role == 'Checker Factory') {
                $actualCheckedCount = DB::table('actuals')
                    ->join('employees', 'actuals.employee_id', '=', 'employees.id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->where('departments.id', '=', $department)
                    ->where('actuals.checked_at', '!=', null)
                    ->whereMonth('actuals.date', $month)
                    ->whereYear('actuals.date', $year)
                    ->select(DB::raw('count(actuals.id) as total_checked'), 'departments.id as department_id')
                    ->orderBy('actuals.input_at', 'desc')
                    ->groupBy('departments.id')
                    ->first();

                $actualCheckedCountDept = DB::table('department_actuals')
                    ->join('departments', 'department_actuals.department_id', '=', 'departments.id')
                    ->where('departments.id', '=', $department)
                    ->where('department_actuals.checked_at', '!=', null)
                    ->whereMonth('department_actuals.date', $month)
                    ->whereYear('department_actuals.date', $year)
                    ->select(DB::raw('count(department_actuals.id) as total_checked'), 'departments.id as department_id')
                    ->orderBy('department_actuals.input_at', 'desc')
                    ->groupBy('departments.id')
                    ->first();

                $targetUnitCountAll = DB::table('target_units')
                    ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
                    ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
                    ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                    ->where('targets.is_active', '=', 1)
                    ->where('departments.id', '=', $department)
                    ->whereYear('targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->first();

                $targetUnitCountAllDept = DB::table('target_units')
                    ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
                    ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
                    ->where('department_targets.is_active', '=', 1)
                    ->where('departments.id', '=', $department)
                    ->whereYear('department_targets.date', $year)
                    ->select(
                        'departments.id as department_id',
                        DB::raw('count(target_units.target_1) as total_1'),
                        DB::raw('count(target_units.target_2) as total_2'),
                        DB::raw('count(target_units.target_3) as total_3'),
                        DB::raw('count(target_units.target_4) as total_4'),
                        DB::raw('count(target_units.target_5) as total_5'),
                        DB::raw('count(target_units.target_6) as total_6'),
                        DB::raw('count(target_units.target_7) as total_7'),
                        DB::raw('count(target_units.target_8) as total_8'),
                        DB::raw('count(target_units.target_9) as total_9'),
                        DB::raw('count(target_units.target_10) as total_10'),
                        DB::raw('count(target_units.target_11) as total_11'),
                        DB::raw('count(target_units.target_12) as total_12'),
                    )
                    ->groupBy('departments.id')
                    ->first();
            }


            $departments = DB::table('departments')->where('departments.id', '=', $authDept)->get();
            $countEmployees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->select(DB::raw('count(employees.id) as total_employee'), 'departments.name as department_name', 'departments.id as department_id')->whereIn('departments.name', $deptList)->groupBy(['departments.name', 'departments.id'])->get();

            $acc = $actualCheckedCount;
            $accd = $actualCheckedCountDept;

            // // Step 1: Get all KPI codes from the targets table
            // $allKpiCodes = DB::table('targets')
            //     ->join('employees', 'targets.employee_id', '=', 'employees.id')
            //     ->join('departments', 'employees.department_id', '=', 'departments.id')
            //     ->whereIn('departments.name', $departmentNames)
            //     // ->whereMonth('date', $month)
            //     // ->whereYear('date', $year)
            //     ->whereIn('departments.name', $departmentNames)
            //     ->select('targets.code')
            //     ->pluck('targets.code')
            //     ->toArray();

            // // Step 2: Get all KPI codes that have actuals inputted for the current month
            // $inputtedKpiCodes = DB::table('actuals')
            //     ->join('employees', 'actuals.employee_id', '=', 'employees.id')
            //     ->join('departments', 'employees.department_id', '=', 'departments.id')
            //     ->whereIn('departments.name', $departmentNames)
            //     ->whereMonth('date', $month)
            //     ->whereYear('date', $year)
            //     ->select('kpi_code')
            //     ->pluck('kpi_code')
            //     ->toArray();

            // Step 3: Find missing KPI codes
            // $missingKpiCodes = array_diff($allKpiCodes, $inputtedKpiCodes);
            // dd($missingKpiCodes, $allKpiCodes, $inputtedKpiCodes);


            // dd($actualCheckedCountGroup, $actualCheckedCountDeptGroup, $targetUnitCountAll, $targetUnitCountAllDept);

            // dd($acc, $accd, $totals);
            // dd($actualFilled);
            // dd($acc, $accd);
            // $tgUnitAll = $targetUnitCountAll;
            // $tgUnitAllDept = $targetUnitCountAllDept;
            $totalTgUnitAll = $totals;
            // dd($countEmployees);
            // dd($actualFilledCount, $actualFilledCountDept->tosSql());
            // dd($totals['total_1']);
            // dd($totalTgUnitAll, $targetUnitCountAll, $targetUnitCountAllDept, $actualCheckedCount, $actualCheckedCountDept);

            // dd($actualFilledCheck, $actualCheckedCheck, $actualApproved, $actualChecked, $countEmployees);
            return view('logs/log-input', [
                'title' => 'Log Input',
                'desc' => 'History',
                'actualFilledCheck' => $actualFilledCheck,
                'actualCheckedCheck' => $actualCheckedCheck,
                'actualApproved' => $actualApproved,
                'actualFilled' => $actualFilled,
                'actualChecked' => $actualChecked,
                'departments' => $departments,
                'countEmployees' => $countEmployees,
                'actualFilledCount' => $actualFilledCount,
                'actualFilledCountDept' => $actualFilledCountDept,
                'actualCheckedCount' => $acc,
                'actualCheckedCountDept' => $accd,
                'totalTgUnitAll' => $totalTgUnitAll,
                'targetUnitCountAll' => $targetUnitFilledCountAll,
                'targetUnitCountAllDept' => $targetUnitFilledCountAllDept,
                'allDept' => $allDept,
                'titlePage' => $titlePage,
                'actualCheckedCountGroup' => $actualCheckedCountGroup,
                'actualCheckedCountDeptGroup' => $actualCheckedCountDeptGroup,
            ]);
        } else if ($department) {
            return view('logs/log-input', [
                'title' => 'Log Input',
                'desc' => 'History',
                'titlePage' => $titlePage,
            ]);
        }
    }

    public function individual(Request $request)
    {

        $department = $request->query('department');
        $month = $request->query('month');
        $year = $request->query('year');
        $allDept = Department::all();

        if ($department == 'All Dept' && $month && $year) {
            $employees = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('employees.*', 'departments.name as department')
                ->paginate(20)
                ->appends(['department' => $department, 'month' => $month, 'year' => $year]);


            $employeesInput = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->select('employees.id', 'employees.name', 'departments.name as department', DB::raw('MAX(actuals.input_at) as latest_input_at'), DB::raw('MAX(actuals.checked_at) as latest_checked_at'), DB::raw('MAX(actuals.approved_at) as latest_approved_at'))
                ->groupBy('employees.id', 'employees.name', 'departments.name')
                ->orderBy('latest_input_at', 'desc')
                ->get();


            // dd($employeesInput);




            return view('logs/log-input-individual', [
                'title' => 'Log Input Individual',
                'desc' => 'History',
                'employeesInput' => $employeesInput,
                'employees' => $employees,
                'department' => $allDept,

            ]);
        } elseif ($department && $month && $year) {

            $employees = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('employees.*', 'departments.name as department')
                ->where('employees.department_id',  '=', $department)->paginate(20)
                ->appends(['department' => $department, 'month' => $month, 'year' => $year]);


            $employeesInput = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->whereMonth('actuals.created_at', '=', $month)
                ->whereYear('actuals.created_at', '=', $year)
                ->where('departments.id', '=', $department)
                ->select('employees.id', 'employees.name', 'departments.name as department', DB::raw('MAX(actuals.input_at) as latest_input_at'), DB::raw('MAX(actuals.checked_at) as latest_checked_at'), DB::raw('MAX(actuals.approved_at) as latest_approved_at'))
                ->groupBy('employees.id', 'employees.name', 'departments.name')
                ->orderBy('latest_input_at', 'desc')
                ->get();



            return view('logs/log-input-individual', [
                'title' => 'Log Input Individual',
                'desc' => 'History',
                'employeesInput' => $employeesInput,
                'employees' => $employees,
                'department' => $allDept,

            ]);
        } else if ($department) {
            return view('logs/log-input-individual', [
                'title' => 'Log Input',
                'desc' => 'History',
            ]);
        }
    }

    public function indexMonitoringEmployee(Request $request)
    {
        $semesterQuery = $request->query('semester');
        $yearQuery = $request->query('year');
        $statusQuery = $request->query('status');
        $deptList = DB::table('departments')->select('name', 'id')->get();
        $statusList = DB::table('employees')->select('status')->distinct()->get();
        $year = $request->year;
        $department = $request->department;
        $semester = $request->semester;
        $status = $request->status;

        $totalTarget = DB::table('target_units')
            ->leftJoin('targets', 'target_units.id', '=', 'targets.target_unit_id')
            ->leftJoin('employees', 'targets.employee_id', '=', 'employees.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->whereYear('targets.date', $yearQuery)
            ->select(
                'employees.id as employee_id',
                DB::raw('count(target_units.target_1) as total_1'),
                DB::raw('count(target_units.target_2) as total_2'),
                DB::raw('count(target_units.target_3) as total_3'),
                DB::raw('count(target_units.target_4) as total_4'),
                DB::raw('count(target_units.target_5) as total_5'),
                DB::raw('count(target_units.target_6) as total_6'),
                DB::raw('count(target_units.target_7) as total_7'),
                DB::raw('count(target_units.target_8) as total_8'),
                DB::raw('count(target_units.target_9) as total_9'),
                DB::raw('count(target_units.target_10) as total_10'),
                DB::raw('count(target_units.target_11) as total_11'),
                DB::raw('count(target_units.target_12) as total_12'),

            )
            ->groupBy('employees.id')
            ->get();

        // dd($totalTarget);

        if ($department == 'all' && $year && $semester && $status) {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('employees.*', 'departments.name as department',)
                ->where('employees.status', '=', $status)
                ->where('is_active', '=', 1)
                ->orderBy('departments.id', 'asc')
                ->orderBy('employees.id', 'asc')
                ->paginate(20)
                ->appends(['department' => $department, 'semester' => $semester, 'year' => $year, 'status' => $status]);
            $actuals = DB::table('actuals')
                ->where('actuals.semester', '=', $semester)
                ->whereNotNull('record_file')
                ->whereYear('actuals.date', $yearQuery)
                ->select(DB::raw('count(actuals.id) as total'), 'actuals.employee_id', DB::raw('MONTH(actuals.date) as month'))
                ->groupBy('actuals.employee_id', DB::raw('MONTH(actuals.date)'))
                ->get();
        } elseif ($department == 'all' && $year && $semester) {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('employees.*', 'departments.name as department',)
                ->where('is_active', '=', 1)
                ->orderBy('departments.id', 'asc')
                ->orderBy('employees.id', 'asc')
                ->paginate(20)
                ->appends(['department' => $department, 'semester' => $semester, 'year' => $year, 'status' => $status]);
            $actuals = DB::table('actuals')
                ->where('actuals.semester', '=', $semester)
                ->whereNotNull('record_file')
                ->whereYear('actuals.date', $yearQuery)
                ->select(DB::raw('count(actuals.id) as total'), 'actuals.employee_id', DB::raw('MONTH(actuals.date) as month'))
                ->groupBy('actuals.employee_id', DB::raw('MONTH(actuals.date)'))
                ->get();
        } elseif ($department && $year && $semester && $status) {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('employees.*', 'departments.name as department',)
                ->where('departments.id', '=', $department)
                ->where('employees.status', '=', $status)
                ->where('is_active', '=', 1)
                ->orderBy('departments.id', 'asc')
                ->orderBy('employees.id', 'asc')
                ->paginate(20)
                ->appends(['department' => $department, 'semester' => $semester, 'year' => $year, 'status' => $status]);
            $actuals = DB::table('actuals')
                ->where('actuals.semester', '=', $semester)
                ->whereNotNull('record_file')
                ->whereYear('actuals.date', $yearQuery)
                ->select(DB::raw('count(actuals.id) as total'), 'actuals.employee_id', DB::raw('MONTH(actuals.date) as month'))
                ->groupBy('actuals.employee_id', DB::raw('MONTH(actuals.date)'))
                ->get();
        } elseif ($department && $year && $semester) {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('employees.*', 'departments.name as department',)
                ->where('departments.id', '=', $department)
                ->where('is_active', '=', 1)
                ->orderBy('departments.id', 'asc')
                ->orderBy('employees.id', 'asc')
                ->paginate(20)
                ->appends(['department' => $department, 'semester' => $semester, 'year' => $year, 'status' => $status]);
            $actuals = DB::table('actuals')
                ->where('actuals.semester', '=', $semester)
                ->whereNotNull('record_file')
                ->whereYear('actuals.date', $yearQuery)
                ->select(DB::raw('count(actuals.id) as total'), 'actuals.employee_id', DB::raw('MONTH(actuals.date) as month'))
                ->groupBy('actuals.employee_id', DB::raw('MONTH(actuals.date)'))
                ->get();
        } elseif ($semester && $year && $status) {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('employees.*', 'departments.name as department',)
                ->where('employees.status', '=', $status)
                ->where('is_active', '=', 1)
                ->orderBy('departments.id', 'asc')
                ->orderBy('employees.id', 'asc')
                ->paginate(20)
                ->appends(['department' => $department, 'semester' => $semester, 'year' => $year, 'status' => $status]);
            $actuals = DB::table('actuals')
                ->where('actuals.semester', '=', $semester)
                ->whereNotNull('record_file')
                ->whereYear('actuals.date', $yearQuery)
                ->select(DB::raw('count(actuals.id) as total'), 'actuals.employee_id', DB::raw('MONTH(actuals.date) as month'))
                ->groupBy('actuals.employee_id', DB::raw('MONTH(actuals.date)'))
                ->get();
        } else {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('employees.*', 'departments.name as department',)
                ->where('is_active', '=', 1)
                ->orderBy('departments.id', 'asc')
                ->orderBy('employees.id', 'asc')
                ->paginate(20)
                ->appends(['department' => $department, 'semester' => $semesterQuery, 'year' => $yearQuery, 'status' => $statusQuery]);
            $actuals = DB::table('actuals')
                ->where('actuals.semester', '=', $semesterQuery)
                ->whereNotNull('record_file')
                ->whereYear('actuals.date', $yearQuery)
                ->select(DB::raw('count(actuals.id) as total'), 'actuals.employee_id', DB::raw('MONTH(actuals.date) as month'))
                ->groupBy('actuals.employee_id', DB::raw('MONTH(actuals.date)'))
                ->get();
        }

        // dd($actuals);

        // dd($employees, $actuals, $actualsCount, $totalTarget);



        return view('logs.log-monitoring-input-employee', [
            'title' => 'Log Monitoring Aktual Employee',
            'desc' => 'History',
            'actuals' => $actuals,
            'totalTarget' => $totalTarget,
            'employees' => $employees,
            'deptList' => $deptList,
            'statusList' => $statusList,
        ]);
    }
    public function indexMonitoringDept(Request $request)
    {
        $semesterQuery = $request->query('semester');
        $yearQuery = $request->query('year');

        $totalTarget = DB::table('target_units')
            ->leftJoin('department_targets', 'target_units.id', '=', 'department_targets.target_unit_id')
            ->leftJoin('departments', 'department_targets.department_id', '=', 'departments.id')
            ->whereYear('department_targets.date', $yearQuery)
            ->select(
                'departments.id as department_id',
                DB::raw('count(target_units.target_1) as total_1'),
                DB::raw('count(target_units.target_2) as total_2'),
                DB::raw('count(target_units.target_3) as total_3'),
                DB::raw('count(target_units.target_4) as total_4'),
                DB::raw('count(target_units.target_5) as total_5'),
                DB::raw('count(target_units.target_6) as total_6'),
                DB::raw('count(target_units.target_7) as total_7'),
                DB::raw('count(target_units.target_8) as total_8'),
                DB::raw('count(target_units.target_9) as total_9'),
                DB::raw('count(target_units.target_10) as total_10'),
                DB::raw('count(target_units.target_11) as total_11'),
                DB::raw('count(target_units.target_12) as total_12'),

            )
            ->groupBy('departments.id')
            ->get();

        // dd($totalTarget);

        $departments = DB::table('departments')->get();
        $actuals = DB::table('department_actuals')
            ->where('department_actuals.semester', '=', $semesterQuery)
            ->whereNotNull('record_file')
            ->whereYear('department_actuals.date', $yearQuery)
            ->select(DB::raw('count(department_actuals.id) as total'), 'department_actuals.department_id', DB::raw('MONTH(department_actuals.date) as month'))
            ->groupBy('department_actuals.department_id', DB::raw('MONTH(department_actuals.date)'))
            ->get();

        // dd($actuals, $totalTarget);

        return view('logs.log-monitoring-input-dept', [
            'title' => 'Log Monitoring Aktual Department',
            'desc' => 'History',
            'actuals' => $actuals,
            'totalTarget' => $totalTarget,
            'departments' => $departments,
        ]);
    }

    public function jobsLog()
    {
        $logPath = storage_path('logs/laravel-worker.log');
        $inputLines = [];
        $check1Lines = [];
        $check2Lines = [];
        $approveLines = [];

        if (file_exists($logPath)) {
            // Read the log file
            $logContent = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Filter lines related to ReminderInputEmail jobs
            foreach ($logContent as $line) {
                if (str_contains($line, 'ReminderInputEmail')) {
                    $inputLines[] = $line;
                }
            }
            foreach ($logContent as $line) {
                if (str_contains($line, 'ReminderCheck1Email')) {
                    $check1Lines[] = $line;
                }
            }

            foreach ($logContent as $line) {
                if (str_contains($line, 'ReminderCheck2Email')) {
                    $check2Lines[] = $line;
                }
            }

            foreach ($logContent as $line) {
                if (str_contains($line, 'ReminderApproveEmail')) {
                    $approveLines[] = $line;
                }
            }
        }

        return view('logs.jobs-log', [
            'title' => 'Jobs Log',
            'desc' => 'History',
            'inputLines' => $inputLines,
            'check1Lines' => $check1Lines,
            'check2Lines' => $check2Lines,
            'approveLines' => $approveLines,
        ]);
    }
}
