<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Actual;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\DepartmentActual;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages;
use Termwind\Components\Dd;

use function Laravel\Prompts\select;

class ReportController extends Controller
{

    public function index(Request $request)
    {
        $department = $request->query('department');
        $employee = $request->query('employee');
        $user = Auth::user();
        $role = $user->role;
        $authDeptID = $user->department_id;
        $email = $user->email;
        $status = $request->status;


        $divDept = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F'])->get();
        $divFAD = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])->get();
        $ws = DB::table('departments')->where('name', '=', 'Workshop')->get();
        $factory = DB::table('departments')->where('name', '=', 'Factory')->get();
        $fsd = DB::table('departments')->where('name', '=', 'FSD')->get();
        $allDept = Department::all();
        $checker1 = DB::table('departments')->where('id', '=', $authDeptID)->get();
        $accFin = DB::table('departments')->whereIn('name', ['Accounting', 'Finance'])->get();
        $deptList = [];

        $listStatus = DB::table('employees')->select('status')
            ->distinct()->get();

        if ($department == 'all' && $status) {
            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')
                ->where('employees.status', '=', $status)
                ->where('employees.is_active', '=', 1)
                ->paginate(20)
                ->appends(['department' => $department, 'status' => $status]);
        } elseif ($department && $status) {
            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')
                ->where('departments.id', $department)
                ->where('employees.status', '=', $status)
                ->where('employees.is_active', '=', 1)
                ->paginate(20)
                ->appends(['department' => $department, 'status' => $status]);
        } elseif ($status) {
            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')
                ->where('employees.status', '=', $status)
                ->where('employees.is_active', '=', 1)
                ->paginate(20)
                ->appends(['department' => $department, 'status' => $status]);
        } elseif ($department == 'all') {
            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')
                ->where('employees.is_active', '=', 1)
                ->paginate(20)
                ->appends(['department' => $department, 'status' => $status]);
        } elseif ($department) {
            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')
                ->where('departments.id', $department)
                ->where('employees.is_active', '=', 1)
                ->paginate(20)
                ->appends(['department' => $department, 'status' => $status]);
        } elseif ($employee) {
            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')
                ->where('employees.id', $employee)
                ->where('employees.is_active', '=', 1)
                ->paginate(20)
                ->appends(['department' => $department, 'status' => $status]);
        }

        // dd($employee, $department);

        if ($employee || $department || $status) {
            if ($role == 'Checker Div 1' || $role == 'Checker Div 2') {
                $deptList = $divDept;
            } elseif ($role == 'FAD' || $email == 'tabrani@bskp.co.id' || $email == 'siswantoko@bskp.co.id') {
                $deptList = $divFAD;
            } elseif ($role == 'Checker WS') {
                $deptList = $ws;
            } elseif ($authDeptID == 12) {
                $deptList = $fsd;
            } elseif ($role == 'Checker 1') {
                $deptList = $checker1;
            } elseif ($email == 'hendi@bskp.co.id') {
                $deptList = $accFin;
            } elseif ($role == 'Checker Factory') {
                $deptList = $factory;
            } elseif ($role == 'Approver' || $role == 'Mng Approver') {
                $deptList = $allDept;
            }
        }

        return view('report.list-employee-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList, 'departments' => $departments, 'listStatus' => $listStatus,]);
    }

    public function indexDept()
    {
        $user = Auth::user();
        $role = $user->role;
        $authDept = $user->department_id;
        $email = $user->email;

        $divDept = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F'])->get();
        $divFAD = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])->get();
        $ws = DB::table('departments')->where('name', '=', 'Workshop')->get();
        $factory = DB::table('departments')->where('name', '=', 'Factory')->get();
        $accFin = DB::table('departments')->whereIn('name', ['Accounting', 'Finance'])->get();
        $allDept = Department::all();

        if ($role == 'Checker Div 1' || $role == 'Checker Div 2') {
            $deptList = $divDept;
            return view('report.list-department-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList]);
        } else if ($role == 'FAD' || $email == 'tabrani@bskp.co.id' || $email == 'siswantoko@bskp.co.id') {
            $deptList = $divFAD;
            return view('report.list-department-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList]);
        } else if ($role == 'Checker WS') {
            $deptList = $ws;
            return view('report.list-department-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList]);
        } else if ($email == 'hendi@bskp.co.id') {
            $deptList = $accFin;
            return view('report.list-department-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList]);
        } else if ($role == 'Checker Factory') {
            $deptList = $factory;
            return view('report.list-department-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList]);
        } else if ($role == 'Approver' || $role == 'Mng Approver') {
            $deptList = $allDept;
            return view('report.list-department-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList]);
        } else {
            $deptList = DB::table('departments')->where('departments.id', $authDept)
                ->get();
            return view('report.list-department-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList]);
        }
    }

    private function calculation($targetZero, $target, $actual, $trend, $recordFile, $unit, $period, $totalPercentage)
    {
        // dump($target, $actual);
        $recordFileCheck = ($recordFile !== null) ? 'yes' : 'no';
        $zeroStatus = ($targetZero === 0 && $recordFileCheck == 'yes') ? 'yes' : 'no';
        $oneStatus = ($targetZero === 1 && $recordFileCheck == 'yes') ? 'yes' : 'no';


        if ($oneStatus == 'yes' && ($unit != 'Tgl' || $unit != 'tgl')) {
            if ($actual == 0) {
                $oneCalc = '0%';
            } elseif ($actual == 1) {
                $oneCalc = '100%';
            } elseif ($actual == 2) {
                $oneCalc = '105%';
            } else if ($actual == 3) {
                $oneCalc = '110%';
            } else if ($actual == 4) {
                $oneCalc = '115%';
            } else if ($actual >= 5) {
                $oneCalc = '120%';
            } else {
                $oneCalc = '0%';
            }
            $percentageValue = $oneCalc;
            return $percentageValue;
        } elseif ($zeroStatus == 'yes') {
            if ($actual == 0) {
                $zeroCalc = '100%';
            } elseif ($actual == 1) {
                $zeroCalc = '75%';
            } elseif ($actual == 2) {
                $zeroCalc = '35%';
            } else if ($actual == 3) {
                $zeroCalc = '25%';
            } else if ($actual == 4) {
                $zeroCalc = '10%';
            } else if ($actual >= 5) {
                $zeroCalc = '0%';
            } else {
                $zeroCalc = '0%';
            }
            $percentageValue = $zeroCalc;
            return $percentageValue;
        } elseif ($unit == 'Tgl' || $unit == 'tgl') {
            $percentageValue = Averages::average($totalPercentage);
        } elseif ($trend == 'Negatif' || $trend == 'negatif') {
            $percentageValue = ($target != 0 && $actual != 0) ? ($target / $actual) * 100 : 0;
        } elseif ($trend == 'Positif'  || $trend == 'positif') {
            $percentageValue = ($target != 0 && $actual != 0) ? ($actual / $target) * 100 : 0;
        } else {
            $percentageValue = 0;
            $oneCalc = 0;
            $zeroCalc = 0;
        }
        return $percentageValue;
    }
    public function show($id, Request $request)
    {
        $semester = $request->query('semester');
        $year = $request->query('year');
        $employee = Employee::find($id);
        $userCreds = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('employees.id', $id)
            ->select('employees.*', 'departments.name as department', 'employees.name as employee', 'employees.id as employee_id')
            ->first();
        // dd($userCreds);

        // if (!$employee) {
        //     abort(404, 'Employee not found');
        // }

        if ($semester && $year) {

            $targets = DB::table('targets')
                ->leftJoin('target_units', 'targets.target_unit_id', '=', 'target_units.id')
                ->select('targets.*', 'target_units.*')
                ->where('employee_id', $id)
                ->where('targets.is_active', '=', true)
                ->where(DB::raw('YEAR(targets.date)'), $year)
                ->get();

            $inactiveTarget = DB::table('targets')
                ->leftJoin('target_units', 'targets.target_unit_id', '=', 'target_units.id')
                ->select('targets.*', 'target_units.*')
                ->where('employee_id', $id)
                ->where('targets.is_active', '=', false)
                ->where(DB::raw('YEAR(targets.date)'), $year)
                ->get();

            $actuals = DB::table('actuals')
                ->leftJoin('employees', 'actuals.employee_id', '=', 'employees.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->select('actuals.date as date', 'actuals.employee_id as employee_id', 'actuals.kpi_item', 'actuals.kpi_code as kpi_code', 'actuals.kpi_weighting', 'actuals.kpi_percentage as achievement', 'actuals.*', 'employees.name as name', 'employees.email as email', 'departments.name as department', 'employees.occupation as occupation', 'employees.nik as nik', 'actuals.semester as semester', 'actuals.date as year', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.record_file', 'actuals.id as actual_id', 'actuals.status as status', 'actuals.trend', 'actuals.kpi_unit', 'actuals.review_period', 'departments.id as department_id')
                ->where('actuals.employee_id', $id)
                ->where('actuals.semester', $semester)
                ->where(DB::raw('YEAR(actuals.date)'), $year)
                ->orderBy(DB::raw('MONTH(actuals.date)'), 'desc')
                ->get();

            // dd($targets, $actuals);
            // sum bobot
            $targetWeightingSum = DB::table('targets')
                ->select('weighting')
                ->where('employee_id', $id)
                ->where(DB::raw('YEAR(targets.date)'), $year)
                ->get();

            $targetWeightingSum->transform(function ($item) {
                $item->weighting = floatval(str_replace('%', '', $item->weighting));
                return $item;
            });

            $sumWeighting = $targetWeightingSum->sum('weighting');



            // if ($actuals->isEmpty()) {
            //     return view('components/404-page-report');
            // }



            $groupedData = $actuals->groupBy('kpi_item');
            // dd($targets, $groupedData);


            // Hitung total target dan actual untuk setiap kelompok
            $totals = $groupedData->map(function ($group) {
                $firstItem = $group->first();
                $unitItem = $firstItem->kpi_unit;

                if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                    $totalTarget = $group->avg(function ($item) {
                        return (float) $item->target;
                    });

                    $totalActual = $group->avg(function ($item) {
                        return (float) $item->actual;
                    });

                    $totalPercentage = $group->avg(function ($item) {
                        return (float) $item->kpi_percentage;
                    });
                } else {
                    $totalTarget = $group->sum(function ($item) {
                        return (float) $item->target;
                    });

                    $totalActual = $group->sum(function ($item) {
                        return (float) $item->actual;
                    });

                    $totalPercentage = $group->sum(function ($item) {
                        return (float) $item->kpi_percentage;
                    });
                }


                $trendItem = $firstItem->trend;
                $recordFileItem = $firstItem->record_file;
                $periodItem = $firstItem->review_period;
                $target = $firstItem->target;
                $percentageCalc = $this->calculation($target, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                $weight = floatval($group->first()->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                $totalAchievementWeight = $convertedCalc * $weight / 100;

                return [
                    'total_target' => $totalTarget,
                    'total_actual' => $totalActual,
                    'percentageCalc' => $convertedCalc,
                    'weight' => $weight,
                    'total_achievement_weight' => $totalAchievementWeight,
                    'trend' => $trendItem,
                ];
            });

            // dd($totals);

            return view('report.employee-report', ['title' => 'Report', 'desc' => 'Employee Report', 'employee' => $employee, 'actuals' => $actuals, 'targets' => $targets, 'totals' => $totals, 'sumWeighting' => $sumWeighting, 'userCreds' => $userCreds, 'idParam' => $id, 'inactiveTargets' => $inactiveTarget]);
        } else {

            return view('components/404-page-report');
        }
    }

    public function department($id, Request $request)
    {
        $semester = $request->query('semester');
        $year = $request->query('year');
        $departmentCreds = DB::table('departments')->where('id', $id)->first();

        if ($semester && $year) {

            $targets = DB::table('department_targets')
                ->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
                ->select('department_targets.*', 'target_units.*')
                ->where('department_id', $id)
                ->where('department_targets.is_active', '=', true)
                ->where(DB::raw('YEAR(department_targets.date)'), $year)
                ->get();

            $inactiveTarget = DB::table('department_targets')
                ->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
                ->select('department_targets.*', 'target_units.*')
                ->where('department_id', $id)
                ->where('department_targets.is_active', '=', false)
                ->where(DB::raw('YEAR(department_targets.date)'), $year)
                ->get();

            $actuals = DB::table('department_actuals')
                ->leftJoin('departments', 'department_actuals.department_id', '=', 'departments.id')
                ->select('department_actuals.date as date', 'department_actuals.department_id as department_id', 'department_actuals.kpi_item', 'department_actuals.kpi_code as kpi_code', 'department_actuals.kpi_weighting', 'department_actuals.kpi_percentage as achievement', 'department_actuals.semester as semester', DB::raw('YEAR(department_actuals.date) as year'), 'department_actuals.target', 'department_actuals.actual', 'department_actuals.kpi_percentage', 'department_actuals.record_file', 'department_actuals.id as department_actual_id', 'department_actuals.status as status', 'departments.name as department', 'department_actuals.trend', 'department_actuals.kpi_unit', 'department_actuals.kpi_unit', 'department_actuals.review_period', 'department_actuals.*')
                ->where('department_actuals.department_id', $id)
                ->where('department_actuals.semester', $semester)
                ->where(DB::raw('YEAR(department_actuals.date)'), $year)
                ->orderBy(DB::raw('MONTH(department_actuals.date)'))->get();


            // if ($actuals->isEmpty()) {
            //     return view('components/404-page-report');
            // }
            // sum bobot
            $targetWeightingSum = DB::table('department_targets')
                ->select('weighting')
                ->where('department_id', $id)
                ->where(DB::raw('YEAR(department_targets.date)'), $year)
                ->get();

            $targetWeightingSum->transform(function ($item) {
                $item->weighting = floatval(str_replace('%', '', $item->weighting));
                return $item;
            });

            $sumWeighting = $targetWeightingSum->sum('weighting');

            // if ($actuals->isEmpty()) {
            //     abort(404, 'No actuals found for the given year and semester');
            // }

            $groupedData = $actuals->groupBy('kpi_code');
            // dd($groupedData, $targets);

            // Hitung total target dan actual untuk setiap kelompok
            $totals = $groupedData->map(function ($group) {
                $firstItem = $group->first();
                $unitItem = $firstItem->kpi_unit;

                $zeroCheck = ($firstItem->target == 0) ? 'yes' : 'no';
                if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam' || $zeroCheck == 'yes') {
                    $totalTarget = $group->avg(function ($item) {
                        return (float) $item->target;
                    });

                    $totalActual = $group->avg(function ($item) {
                        return (float) $item->actual;
                    });

                    $totalPercentage = $group->avg(function ($item) {
                        return (float) $item->kpi_percentage;
                    });
                } else {
                    $totalTarget = $group->sum(function ($item) {
                        return (float) $item->target;
                    });

                    $totalActual = $group->sum(function ($item) {
                        return (float) $item->actual;
                    });

                    $totalPercentage = $group->sum(function ($item) {
                        return (float) $item->kpi_percentage;
                    });
                }

                $trendItem = $firstItem->trend;
                $recordFileItem = $firstItem->record_file;
                $periodItem = $firstItem->review_period;
                $target = $firstItem->target;
                $percentageCalc = $this->calculation($target, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);


                $convertedCalc = floatval(str_replace('%', '', $percentageCalc));
                // dd($convertedCalc);


                $weight = floatval($group->first()->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                $totalAchivementWeight = $convertedCalc * $weight / 100;


                return [
                    'total_target' => $totalTarget,
                    'total_actual' => $totalActual,
                    'weight' => $weight,
                    'percentageCalc' => $convertedCalc,
                    'total_achievement_weight' => $totalAchivementWeight,
                ];
            });

            // dd($totals);


            return view('report.department-report', ['title' => 'Report', 'desc' => 'Summary KPI Dept', 'actuals' => $actuals, 'targets' => $targets, 'totals' => $totals, 'sumWeighting' => $sumWeighting, 'departmentCreds' => $departmentCreds, 'idParam' => $id, 'inactiveTargets' => $inactiveTarget]);
        } else {
            return view('components/404-page-report');
        }
    }

    public function summaryDept(Request $request)
    {
        $department = $request->query('department');
        // dd($department);
        $yearToShow = $request->query('year');
        $status = $request->query('status');
        $allDept = Department::all();
        $allStatus = Employee::select('status')->distinct()->get();

        if ($yearToShow && $department && $status) {
            $employees = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->select('departments.name as dept', 'employees.name as name', 'employees.nik', 'employees.occupation', 'employees.id as employee_id', 'department_id')
                ->whereIn('departments.id', $department)
                ->where('employees.status', '=', $status)
                ->where('employees.is_active', '=', 1)
                ->paginate(35)
                ->appends(['year' => $yearToShow, 'department' => $department, 'occupation' => $status]);
            // dd($employees);

            $employeeIds = $employees->pluck('employee_id');
            $departmentIds = $employees->pluck('department_id');
            // dd($departmentIds);

            // Fetch actuals data for the paginated employees
            $semester1Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('targets', 'targets.employee_id', '=', 'employees.id')
                ->select('actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('targets.is_active', '=', true)
                ->where('actuals.semester', '=', '1')
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id',  $departmentIds)
                ->whereIn('employees.id', $employeeIds)
                ->get();

            $semester2Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('targets', 'targets.employee_id', '=', 'employees.id')
                ->select('actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('targets.is_active', '=', true)
                ->where('actuals.semester', '=', '2')
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->whereIn('employees.id', $employeeIds)
                ->get();

            // dd($semester1Actuals, $semester2Actuals);


            $semester1ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', true)
                ->where('department_actuals.semester', '=', '1')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();

            $semester2ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', true)
                ->where('department_actuals.semester', '=', '2')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();

            $actualsGroup1 = $semester1Actuals->groupBy(['employee_id', 'kpi_item']);
            $actualsGroup2 = $semester2Actuals->groupBy(['employee_id', 'kpi_item']);
            $actualsDeptGroup1 = $semester1ActualsDept->groupBy(['department_id', 'kpi_code']);
            $actualsDeptGroup2 = $semester2ActualsDept->groupBy(['department_id', 'kpi_code']);

            $semester1Group = $actualsGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {


                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);


                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester2Group = $actualsGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester1DeptGroup = $actualsDeptGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester2DeptGroup = $actualsDeptGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            // dd($semester1Group, $semester2Group, $semester1DeptGroup, $semester2DeptGroup);

            // Calculate the sum of total_achievement_weight for each semester group
            $sumGroupSemester1 = $semester1Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $sumGroupSemester2 = $semester2Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $totalSumSemester = $sumGroupSemester1->map(function ($value, $key) use ($sumGroupSemester2) {
                return (($value + ($sumGroupSemester2[$key] ?? 0)) / 2) * 0.7;
            });


            $sumSemester1Dept = $semester1DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });
            $sumSemester2Dept = $semester2DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });

            $totalSumSemesterDept = $sumSemester1Dept->map(function ($value, $key) use ($sumSemester2Dept) {
                return (($value + ($sumSemester2Dept[$key] ?? 0)) / 2) * 0.3;
            });

            // dd($sumSemester1Dept, $sumSemester2Dept, $totalSumSemester);

            // dd($employees, $semester1Group, $semester2Group, $semester1DeptGroup, $semester2DeptGroup, $sumGroupSemester1, $sumGroupSemester2, $sumSemester1Dept, $sumSemester2Dept, $totalSumSemester, $totalSumSemesterDept);

            return view('report.summary-department-report', [
                'title' => 'Report',
                'desc' => 'Department Report',
                'allDept' => $allDept,
                'allOccupation' => $allStatus,
                'employees' => $employees,
                'semester1Group' => $semester1Group,
                'semester2Group' => $semester2Group,
                'semester1DeptGroup' => $semester1DeptGroup,
                'semester2DeptGroup' => $semester2DeptGroup,
                'sumGroupSemester1' => $sumGroupSemester1,
                'sumGroupSemester2' => $sumGroupSemester2,
                'sumGroupSemester1Dept' => $sumSemester1Dept,
                'sumGroupSemester2Dept' => $sumSemester2Dept,
                'totalSumSemester' => $totalSumSemester,
                'totalSumSemesterDept' => $totalSumSemesterDept,
                // 'totalAll' => $totalAll,
            ]);
        } elseif ($yearToShow && $department) {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('targets', 'targets.employee_id', '=', 'employees.id')
                ->select('departments.name as dept', 'employees.name as name', 'employees.nik', 'employees.occupation', 'employees.id as employee_id', 'department_id')
                ->whereIn('departments.id',  $department)
                ->where('employees.is_active', '=', 1)
                ->where('targets.is_active', '=', true)
                ->groupBy('employees.id')
                ->paginate(35)
                ->appends(['year' => $yearToShow, 'department' => $department, 'occupation' => $status]);
            $inactiveTargetEmployees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('targets', 'targets.employee_id', '=', 'employees.id')
                ->select('departments.name as dept', 'employees.name as name', 'employees.nik', 'employees.occupation', 'employees.id as employee_id', 'department_id')
                ->where('targets.is_active', '=', false)
                ->where('departments.id', '=', $department)
                ->where('employees.is_active', '=', 1)
                ->groupBy('employees.id')
                ->get();
            // dd($employees);

            $activeTargets = DB::table('targets')
                ->select('id', 'employee_id', 'indicator', 'code', 'is_active')
                ->where('is_active', true);

            $inactiveTargets = DB::table('targets')
                ->select('id', 'employee_id', 'indicator', 'code', 'is_active')
                ->where('is_active', false);

            $employeeIds = $employees->pluck('employee_id');
            $departmentIds = $employees->pluck('department_id');
            $employeeInactiveIds = $inactiveTargetEmployees->pluck('employee_id');
            // dd($employeeInactiveIds);


            $semester1Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoinSub($activeTargets, 'targets', function ($join) {
                    $join->on('targets.employee_id', '=', 'actuals.employee_id')
                        ->on('targets.code', '=', 'actuals.kpi_code');
                })
                ->select('targets.is_active as target_is_active', 'actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id', 'actuals.id as actual_id', 'employees.id as employee_id')
                ->where('actuals.semester', '=', '1')
                ->where('targets.is_active', '=', true)
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id',  $departmentIds)
                ->whereIn('targets.employee_id', $employeeIds)
                ->get();

            $semester2Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoinSub($activeTargets, 'targets', function ($join) {
                    $join->on('targets.employee_id', '=', 'actuals.employee_id')
                        ->on('targets.code', '=', 'actuals.kpi_code');
                })
                ->select('actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('actuals.semester', '=', '2')
                ->where('targets.is_active', '=', true)
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->whereIn('employees.id', $employeeIds)
                ->get();
            // dd($semester1Actuals, $semester2Actuals);


            $semester1ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', true)
                ->where('department_actuals.semester', '=', '1')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();

            $semester2ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', true)
                ->where('department_actuals.semester', '=', '2')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();

            $inactiveSemester1Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoinSub($inactiveTargets, 'targets', function ($join) {
                    $join->on('targets.employee_id', '=', 'actuals.employee_id')
                        ->on('targets.code', '=', 'actuals.kpi_code');
                })
                ->select('targets.is_active as target_is_active', 'actuals.*', 'actuals.id as actual_id', 'employees.*', 'employees.id as employee_id', 'departments.name as department_name', 'departments.id as department_id')
                ->where('actuals.semester', '=', '1')
                ->where('targets.is_active', '=', false)
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id',  $departmentIds)
                ->whereIn('targets.employee_id', $employeeInactiveIds)
                ->get();
            // dd($semester1Actuals, $inactiveSemester1Actuals);


            $inactiveSemester2Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoinSub($activeTargets, 'targets', function ($join) {
                    $join->on('targets.employee_id', '=', 'actuals.employee_id')
                        ->on('targets.code', '=', 'actuals.kpi_code');
                })
                ->select('actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('actuals.semester', '=', '2')
                ->where('targets.is_active', '=', false)
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->whereIn('employees.id', $employeeInactiveIds)
                ->get();

            $inactiveSemester1ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', false)
                ->where('department_actuals.semester', '=', '1')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();

            $inactiveSemester2ActualsDept = DB::table('department_actuals')
                ->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', false)
                ->where('department_actuals.semester', '=', '2')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();


            $actualsGroup1 = $semester1Actuals->groupBy(['employee_id', 'kpi_item']);
            $actualsGroup2 = $semester2Actuals->groupBy(['employee_id', 'kpi_item']);
            $actualsDeptGroup1 = $semester1ActualsDept->groupBy(['department_id', 'kpi_code']);
            $actualsDeptGroup2 = $semester2ActualsDept->groupBy(['department_id', 'kpi_code']);

            $inactiveActualsGroup1 = $inactiveSemester1Actuals->groupBy(['employee_id', 'kpi_item']);
            $inactiveActualsGroup2 = $inactiveSemester2Actuals->groupBy(['employee_id', 'kpi_item']);
            $inactiveActualsDeptGroup1 = $inactiveSemester1ActualsDept->groupBy(['department_id', 'kpi_code']);
            $inactiveActualsDeptGroup2 = $inactiveSemester2ActualsDept->groupBy(['department_id', 'kpi_code']);

            // dd($inactiveActualsGroup1, $actualsGroup1);

            $semester1Group = $actualsGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {
                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester2Group = $actualsGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {


                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester1DeptGroup = $actualsDeptGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester2DeptGroup = $actualsDeptGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $inactiveSemester1Group = $inactiveActualsGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $inactiveSemester2Group = $inactiveActualsGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $inactiveSemester1DeptGroup = $inactiveActualsDeptGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $inactiveSemester2DeptGroup = $inactiveActualsDeptGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            // dd($inactiveActualsGroup1, $actualsGroup1);

            // dd($semester1Group, $semester2Group, $semester1DeptGroup, $semester2DeptGroup);

            // Calculate the sum of total_achievement_weight for each semester group
            $sumGroupSemester1 = $semester1Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $sumGroupSemester2 = $semester2Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $totalSumSemester = $sumGroupSemester1->map(function ($value, $key) use ($sumGroupSemester2) {
                return ($value + ($sumGroupSemester2[$key] ?? 0)) * 0.7;
            });


            $sumSemester1Dept = $semester1DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });
            $sumSemester2Dept = $semester2DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });

            $totalSumSemesterDept = $sumSemester1Dept->map(function ($value, $key) use ($sumSemester2Dept) {
                return ($value + ($sumSemester2Dept[$key] ?? 0)) * 0.3;
            });

            $sumInactiveGroupSemester1 = $inactiveSemester1Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $sumInactiveGroupSemester2 = $inactiveSemester2Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $totalSumInactiveSemester = $sumInactiveGroupSemester1->map(function ($value, $key) use ($sumInactiveGroupSemester2) {
                return ($value + ($sumInactiveGroupSemester2[$key] ?? 0)) * 0.7;
            });

            $sumInactiveSemester1Dept = $inactiveSemester1DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });

            $sumInactiveSemester2Dept = $inactiveSemester2DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });

            $totalSumInactiveSemesterDept = $sumInactiveSemester1Dept->map(function ($value, $key) use ($sumInactiveSemester2Dept) {
                return ($value + ($sumInactiveSemester2Dept[$key] ?? 0)) * 0.3;
            });

            // dd($sumSemester1Dept, $sumSemester2Dept, $totalSumSemester);
            // dd($sumGroupSemester1, $sumInactiveGroupSemester1);

            // dd($employees, $semester1Group, $semester2Group, $semester1DeptGroup, $semester2DeptGroup, $sumGroupSemester1, $sumGroupSemester2, $sumSemester1Dept, $sumSemester2Dept, $totalSumSemester, $totalSumSemesterDept);

            return view('report.summary-department-report', [
                'title' => 'Report',
                'desc' => 'Department Report',
                'allDept' => $allDept,
                'allOccupation' => $allStatus,
                'employees' => $employees,
                'inactiveTargetEmployees' => $inactiveTargetEmployees,
                'semester1Group' => $semester1Group,
                'semester2Group' => $semester2Group,
                'semester1DeptGroup' => $semester1DeptGroup,
                'semester2DeptGroup' => $semester2DeptGroup,
                'inactiveSemester1Group' => $inactiveSemester1Group,
                'inactiveSemester2Group' => $inactiveSemester2Group,
                'inactiveSemester1DeptGroup' => $inactiveSemester1DeptGroup,
                'inactiveSemester2DeptGroup' => $inactiveSemester2DeptGroup,
                'sumGroupSemester1' => $sumGroupSemester1,
                'sumGroupSemester2' => $sumGroupSemester2,
                'sumGroupSemester1Dept' => $sumSemester1Dept,
                'sumGroupSemester2Dept' => $sumSemester2Dept,
                'sumInactiveGroupSemester1' => $sumInactiveGroupSemester1,
                'sumInactiveGroupSemester2' => $sumInactiveGroupSemester2,
                'sumInactiveGroupSemester1Dept' => $sumInactiveSemester1Dept,
                'sumInactiveGroupSemester2Dept' => $sumInactiveSemester2Dept,
                'totalSumSemester' => $totalSumSemester,
                'totalSumSemesterDept' => $totalSumSemesterDept,
                'totalSumInactiveSemester' => $totalSumInactiveSemester,
                'totalSumInactiveSemesterDept' => $totalSumInactiveSemesterDept,

                // 'totalAll' => $totalAll,
            ]);
        } elseif ($yearToShow && $status) {
            $employees = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->select('departments.name as dept', 'employees.name as name', 'employees.nik', 'employees.occupation', 'employees.id as employee_id', 'department_id')
                ->whereIn('employees.status', $status)
                ->where('employees.is_active', '=', 1)
                ->paginate(35)
                ->appends(['year' => $yearToShow, 'department' => $department, 'occupation' => $status]);
            // dd($employees);

            $employeeIds = $employees->pluck('employee_id');
            $departmentIds = $employees->pluck('department_id');
            // dd($employeeIds);

            // Fetch actuals data for the paginated employees
            $semester1Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('targets', 'targets.employee_id', '=', 'employees.id')
                ->select('actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('actuals.semester', '=', '1')
                ->where('targets.is_active', '=', true)
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id',  $departmentIds)
                ->whereIn('employees.id', $employeeIds)
                ->get();

            $semester2Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('targets', 'targets.employee_id', '=', 'employees.id')
                ->select('actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('actuals.semester', '=', '2')
                ->where('targets.is_active', '=', true)
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->whereIn('employees.id', $employeeIds)
                ->get();

            // dd($semester1Actuals, $semester2Actuals);
            $semester1ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', true)
                ->where('department_actuals.semester', '=', '1')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();

            $semester2ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', true)
                ->where('department_actuals.semester', '=', '2')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();



            // Active targets
            $actualsGroup1 = $semester1Actuals->groupBy(['employee_id', 'kpi_item']);
            $actualsGroup2 = $semester2Actuals->groupBy(['employee_id', 'kpi_item']);
            $actualsDeptGroup1 = $semester1ActualsDept->groupBy(['department_id', 'kpi_code']);
            $actualsDeptGroup2 = $semester2ActualsDept->groupBy(['department_id', 'kpi_code']);


            $semester1Group = $actualsGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester2Group = $actualsGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester1DeptGroup = $actualsDeptGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {


                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester2DeptGroup = $actualsDeptGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {

                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    $periodItem = $firstItem->review_period;

                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }

                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });


            // dd($inactiveSemester1Group, $inactiveSemester2Group, $inactiveSemester1DeptGroup, $inactiveSemester2DeptGroup);
            // dd($semester1Group, $semester2Group, $semester1DeptGroup, $semester2DeptGroup);

            // Calculate the sum of total_achievement_weight for each semester group
            $sumGroupSemester1 = $semester1Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $sumGroupSemester2 = $semester2Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $totalSumSemester = $sumGroupSemester1->map(function ($value, $key) use ($sumGroupSemester2) {
                return ($value + ($sumGroupSemester2[$key] ?? 0)) * 0.7;
            });


            $sumSemester1Dept = $semester1DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });
            $sumSemester2Dept = $semester2DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });

            $totalSumSemesterDept = $sumSemester1Dept->map(function ($value, $key) use ($sumSemester2Dept) {
                return ($value + ($sumSemester2Dept[$key] ?? 0)) * 0.3;
            });

            // dd($sumSemester1Dept, $sumSemester2Dept, $totalSumSemester);

            // dd($employees, $semester1Group, $semester2Group, $semester1DeptGroup, $semester2DeptGroup, $sumGroupSemester1, $sumGroupSemester2, $sumSemester1Dept, $sumSemester2Dept, $totalSumSemester, $totalSumSemesterDept);

            return view('report.summary-department-report', [
                'title' => 'Report',
                'desc' => 'Department Report',
                'allDept' => $allDept,
                'allOccupation' => $allStatus,
                'employees' => $employees,
                'semester1Group' => $semester1Group,
                'semester2Group' => $semester2Group,
                'semester1DeptGroup' => $semester1DeptGroup,
                'semester2DeptGroup' => $semester2DeptGroup,
                'sumGroupSemester1' => $sumGroupSemester1,
                'sumGroupSemester2' => $sumGroupSemester2,
                'sumGroupSemester1Dept' => $sumSemester1Dept,
                'sumGroupSemester2Dept' => $sumSemester2Dept,
                'totalSumSemester' => $totalSumSemester,
                'totalSumSemesterDept' => $totalSumSemesterDept,
                // 'totalAll' => $totalAll,
            ]);
        } elseif ($yearToShow) {

            $employees = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('departments.name as dept', 'employees.name as name', 'employees.nik', 'employees.occupation', 'employees.id as employee_id', 'department_id')
                ->where('employees.is_active', '=', 1)
                ->paginate(35)
                ->appends(['year' => $yearToShow, 'department' => $department, 'occupation' => $status]);
            // dd($employees);

            // Get the employee IDs for the current page
            $employeeIds = $employees->pluck('employee_id');
            $departmentIds = $employees->pluck('department_id');
            // dd($employeeIds);

            // Fetch actuals data for the paginated employees
            $semester1Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('targets', 'targets.employee_id', '=', 'employees.id')
                ->select('actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id', 'targets.is_active')
                ->where('actuals.semester', '=', '1')
                ->where('targets.is_active', '=', true)
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id',  $departmentIds)
                ->whereIn('employees.id', $employeeIds)
                ->get();

            $semester2Actuals = DB::table('actuals')
                ->leftJoin('employees', 'employees.id', '=', 'actuals.employee_id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->leftJoin('targets', 'targets.employee_id', '=', 'employees.id')
                ->select('actuals.*', 'employees.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('actuals.semester', '=', '2')
                ->where('targets.is_active', '=', true)
                ->whereYear('actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->whereIn('employees.id', $employeeIds)
                ->get();

            // dd($semester1Actuals, $semester2Actuals);


            $semester1ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->where('department_actuals.semester', '=', '1')
                ->where('department_targets.is_active', '=', true)
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();

            $semester2ActualsDept = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
                ->leftJoin('department_targets', 'department_targets.department_id', '=', 'departments.id')
                ->select('department_actuals.*', 'departments.name as department_name', 'departments.id as department_id')
                ->where('department_targets.is_active', '=', true)
                ->where('department_actuals.semester', '=', '2')
                ->whereYear('department_actuals.date', '=', $yearToShow)
                ->whereIn('departments.id', $departmentIds)
                ->get();

            $actualsGroup1 = $semester1Actuals->groupBy(['employee_id', 'kpi_item']);
            $actualsGroup2 = $semester2Actuals->groupBy(['employee_id', 'kpi_item']);
            $actualsDeptGroup1 = $semester1ActualsDept->groupBy(['department_id', 'kpi_code']);
            $actualsDeptGroup2 = $semester2ActualsDept->groupBy(['department_id', 'kpi_code']);

            $semester1Group = $actualsGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {
                    $firstItem = $subGroup->first();
                    $unitItem = $firstItem->kpi_unit;

                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }


                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester2Group = $actualsGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {


                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester1DeptGroup = $actualsDeptGroup1->map(function ($group) {
                return $group->map(function ($subGroup) {


                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            $semester2DeptGroup = $actualsDeptGroup2->map(function ($group) {
                return $group->map(function ($subGroup) {


                    $firstItem = $subGroup->first();
                    $trendItem = $firstItem->trend;
                    $recordFileItem = $firstItem->record_file;
                    $unitItem = $firstItem->kpi_unit;
                    if ($unitItem == 'Tgl' || $unitItem == 'tgl' || $unitItem == '%' || $unitItem == 'Kg/Tap' || $unitItem == 'Rp/Kg' || $unitItem == 'mm' || $unitItem == 'M3' || $unitItem == 'Hari' || $unitItem == 'Freq "0"' || $unitItem == 'Jam') {
                        $totalTarget = $subGroup->avg(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->avg(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->avg(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    } else {
                        $totalTarget = $subGroup->sum(function ($item) {
                            return (float) $item->target;
                        });

                        $totalActual = $subGroup->sum(function ($item) {
                            return (float) $item->actual;
                        });

                        $totalPercentage = $subGroup->sum(function ($item) {
                            return (float) $item->kpi_percentage;
                        });
                    }
                    $periodItem = $firstItem->review_period;
                    $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $recordFileItem, $unitItem, $periodItem, $totalPercentage);

                    $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                    $weight = floatval($firstItem->kpi_weighting); // Ambil bobot dari item pertama dalam grup
                    $totalAchievementWeight = $convertedCalc * $weight / 100;

                    return [
                        'total_target' => $totalTarget,
                        'total_actual' => $totalActual,
                        'weight' => $weight,
                        'percentageCalc' => $convertedCalc,
                        'total_achievement_weight' => $totalAchievementWeight,
                    ];
                });
            });

            // dd($semester1Group, $semester2Group, $semester1DeptGroup, $semester2DeptGroup);

            // Calculate the sum of total_achievement_weight for each semester group
            $sumGroupSemester1 = $semester1Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $sumGroupSemester2 = $semester2Group->mapWithKeys(function ($group, $employeeId) {
                return [$employeeId => $group->sum('total_achievement_weight')];
            });

            $totalSumSemester = $sumGroupSemester1->map(function ($value, $key) use ($sumGroupSemester2) {
                return ($value + ($sumGroupSemester2[$key] ?? 0)) * 0.7;
            });


            $sumSemester1Dept = $semester1DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });
            $sumSemester2Dept = $semester2DeptGroup->mapWithKeys(function ($group, $departmentId) {
                return [$departmentId => $group->sum('total_achievement_weight')];
            });

            $totalSumSemesterDept = $sumSemester1Dept->map(function ($value, $key) use ($sumSemester2Dept) {
                return ($value + ($sumSemester2Dept[$key] ?? 0)) * 0.3;
            });

            // dd($sumSemester1Dept, $sumSemester2Dept, $totalSumSemester);

            // dd($employees, $semester1Group, $semester2Group, $semester1DeptGroup, $semester2DeptGroup, $sumGroupSemester1, $sumGroupSemester2, $sumSemester1Dept, $sumSemester2Dept, $totalSumSemester, $totalSumSemesterDept);

            return view('report.summary-department-report', [
                'title' => 'Report',
                'desc' => 'Department Report',
                'allDept' => $allDept,
                'allOccupation' => $allStatus,
                'employees' => $employees,
                'semester1Group' => $semester1Group,
                'semester2Group' => $semester2Group,
                'semester1DeptGroup' => $semester1DeptGroup,
                'semester2DeptGroup' => $semester2DeptGroup,
                'sumGroupSemester1' => $sumGroupSemester1,
                'sumGroupSemester2' => $sumGroupSemester2,
                'sumGroupSemester1Dept' => $sumSemester1Dept,
                'sumGroupSemester2Dept' => $sumSemester2Dept,
                'totalSumSemester' => $totalSumSemester,
                'totalSumSemesterDept' => $totalSumSemesterDept,
                // 'totalAll' => $totalAll,
            ]);
        } else {
            return view('components/404-page');
        }
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

    public function indexDeptTargetReport(Request $request)
    {
        $year = $request->query('year');
        $targets = DB::table('department_targets')->select('department_targets.indicator',)->whereYear('department_targets.date', '=', $year)
            ->groupBy('department_targets.indicator')
            // ->orderBy('indicator', 'desc')
            ->get();

        // dd($targets);

        return view('report.list-kpi-department-report', ['title' => 'Report', 'desc' => 'Summary KPI All Department', 'targets' => $targets]);
    }

    public function departmentTargetReport(Request $request)
    {
        $year = $request->query('year');
        $item = $request->query('item');

        $actuals = DB::table('department_actuals')->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
            ->select('department_actuals.*', 'departments.name as department')
            ->whereYear('department_actuals.date', '=', $year)
            ->where('kpi_item', '=', $item)
            ->get();

        $targets = DB::table('department_targets')->leftJoin('departments', 'departments.id', '=', 'department_targets.department_id')
            ->leftJoin('target_units', 'target_units.id', '=', 'department_targets.target_unit_id')
            ->select('department_targets.*', 'departments.name as department', 'target_units.*')
            ->whereYear('department_targets.date', '=', $year)
            ->where('indicator', '=', $item)->get();

        $indicatorList = DB::table('department_targets')->select('indicator')->whereYear('date', '=', $year)->groupBy('indicator')->get();

        // dd($targets, $actuals);


        // dd($actuals);

        return view('report.target-kpi-department-report', ['title' => 'Summary KPI', 'desc' => 'All Department', 'actuals' => $actuals, 'targets' => $targets, 'indicatorList' => $indicatorList]);
    }
}
