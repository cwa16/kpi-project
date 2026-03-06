<?php
namespace App\Http\Controllers;

use App\Exports\SummaryDeptAnnualExport;
use App\Models\Actual;
use App\Models\Department;
use App\Models\DepartmentActual;
use App\Models\Employee;
use function Laravel\Prompts\select;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages;

class ReportYearController extends Controller
{

    public function index(Request $request)
    {
        $department = $request->query('department');
        $employee   = $request->query('employee');
        $user       = Auth::user();
        $role       = $user->role;
        $authDeptID = $user->department_id;
        $email      = $user->email;
        $status     = $request->status;

        $divDept  = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F'])->get();
        $divFAD   = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])->get();
        $ws       = DB::table('departments')->where('name', '=', 'Workshop')->get();
        $factory  = DB::table('departments')->where('name', '=', 'Factory')->get();
        $fsd      = DB::table('departments')->where('name', '=', 'FSD')->get();
        $allDept  = Department::all();
        $checker1 = DB::table('departments')->where('id', '=', $authDeptID)->get();
        $accFin   = DB::table('departments')->whereIn('name', ['Accounting', 'Finance'])->get();
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

        return view('report.list-employee-report', ['title' => 'Report', 'desc' => 'Department List', 'deptList' => $deptList, 'departments' => $departments, 'listStatus' => $listStatus]);
    }

    public function indexDept()
    {
        $user     = Auth::user();
        $role     = $user->role;
        $authDept = $user->department_id;
        $email    = $user->email;

        $divDept = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F'])->get();
        $divFAD  = DB::table('departments')->whereIn('name', ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD', 'Div 1', 'Div 2'])->get();
        $ws      = DB::table('departments')->where('name', '=', 'Workshop')->get();
        $factory = DB::table('departments')->where('name', '=', 'Factory')->get();
        $accFin  = DB::table('departments')->whereIn('name', ['Accounting', 'Finance'])->get();
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

    public function calculation($targetZero, $target, $actual, $trend, $unit, $totalPercentage)
    {
        // dump($target, $actual);
        $zeroStatus = ($targetZero === "0" || $targetZero == 0) ? 'yes' : 'no';
        // $oneStatus = ($targetZero === "1" || $targetZero == 1) ? 'yes' : 'no';

        // dump($zeroStatus, $targetZero);

        if ($zeroStatus == 'yes') {
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
            if ($unit == 'Freq "0"' || $unit == '%' || $unit == 'Hari') {
                $percentageValue = Averages::average($totalPercentage);
            } else {
                $percentageValue = ($target != 0 && $actual != 0) ? ($target / $actual) * 100 : 0;
            }
        } elseif ($trend == 'Positif' || $trend == 'positif') {
            if ($unit == 'Freq "0"' || $unit == '%') {
                $percentageValue = Averages::average($totalPercentage);
            } else {
                $percentageValue = ($target != 0 && $actual != 0) ? ($actual / $target) * 100 : 0;
            }
        } else {
            $percentageValue = 0;
        }

        return $percentageValue;
    }

    public function show($id, Request $request)
    {
        $semester = $request->query('semester');
        $year     = $request->query('year');
        $employee = Employee::find($id);

        // Ambil data user/department (query lama Anda)
        $userCreds = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('employees.id', $id)
            ->select('employees.*', 'departments.name as department', 'employees.name as employee', 'employees.id as employee_id')
            ->first();

        if ($semester && $year) {

            // 1. AMBIL TARGETS (Master Data)
            // Join ke target_units untuk dapat target_1 s.d target_12
            $targets = DB::table('targets')
                ->leftJoin('target_units', 'targets.target_unit_id', '=', 'target_units.id')
                ->select(
                    'targets.*',
                    'target_units.target_1', 'target_units.target_2', 'target_units.target_3',
                    'target_units.target_4', 'target_units.target_5', 'target_units.target_6',
                    'target_units.target_7', 'target_units.target_8', 'target_units.target_9',
                    'target_units.target_10', 'target_units.target_11', 'target_units.target_12'
                )
                ->where('targets.employee_id', $id)
                ->where('targets.is_active', '=', true)
                ->where(DB::raw('YEAR(targets.date)'), $year)
                ->get();

            // Ambil Targets Inactive (untuk keperluan view saja)
            $inactiveTarget = DB::table('targets')
                ->leftJoin('target_units', 'targets.target_unit_id', '=', 'target_units.id')
                ->select('targets.*', 'target_units.*')
                ->where('employee_id', $id)
                ->where('targets.is_active', '=', false)
                ->where(DB::raw('YEAR(targets.date)'), $year)
                ->get();

            // 2. AMBIL ACTUALS (Data Transaksi)
            $actuals = DB::table('actuals')
                ->leftJoin('employees', 'actuals.employee_id', '=', 'employees.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->select('actuals.date as date', 'actuals.employee_id as employee_id', 'actuals.kpi_item', 'actuals.kpi_code as kpi_code', 'actuals.kpi_weighting', 'actuals.kpi_percentage as achievement', 'actuals.*', 'employees.name as name', 'employees.email as email', 'departments.name as department', 'employees.occupation as occupation', 'employees.nik as nik', 'actuals.semester as semester', 'actuals.date as year', 'actuals.target', 'actuals.actual', 'actuals.kpi_percentage', 'actuals.record_file', 'actuals.id as actual_id', 'actuals.status as status', 'actuals.trend', 'actuals.kpi_unit', 'actuals.review_period', 'departments.id as department_id')
                ->where('actuals.employee_id', $id)
                ->where(DB::raw('YEAR(actuals.date)'), $year)
                ->orderBy(DB::raw('MONTH(actuals.date)'), 'desc')
                ->get();

            // Sum Bobot
            $targetWeightingSum = DB::table('targets')
                ->where('employee_id', $id)
                ->where('is_active', true)
                ->where(DB::raw('YEAR(date)'), $year)
                ->get()
                ->sum(function ($item) {
                    return floatval(str_replace('%', '', $item->weighting));
                });

            // --- LOGIC UTAMA ---
            // Grouping berdasarkan 'indicator' dari tabel TARGETS.
            // Ini memastikan KPI tampil meskipun actual-nya kosong.
            // Pastikan di Query $targets, kolom 'targets.period' sudah di-select!

            $groupedTargets = $targets->groupBy('indicator');

            $totals = $groupedTargets->map(function ($targetGroup) use ($actuals) {
                // Ambil row pertama dari group target ini
                $firstTarget = $targetGroup->first();

                $unitItem  = $firstTarget->unit;      // dari targets.unit
                $kpiItem   = $firstTarget->indicator; // dari targets.indicator
                $trendItem = $firstTarget->trend;
                $period    = $firstTarget->period; // Ambil Periode (Annual, Semester, Quarter, Monthly)

                // --- 1. TENTUKAN PEMBAGI (DIVISOR) BERDASARKAN PERIODE ---
                // Default 12 (untuk Monthly)
                $divisor = 12;

                // Cek periode (gunakan strtolower agar tidak sensitif huruf besar/kecil)
                $periodName = strtolower($period);

                if ($periodName == 'annual') {
                    $divisor = 1;
                } elseif ($periodName == 'semester') {
                    $divisor = 2;
                } elseif ($periodName == 'quarter') {
                    $divisor = 4;
                }

                // --- 2. HITUNG SUM TARGET DARI DB (target_1 ... target_12) ---
                $monthlyTargetsSum = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $col  = 'target_' . $i;
                    // Ambil value, hilangkan koma, ubah ke float
                    $val                = isset($firstTarget->$col) ? (float) str_replace(',', '', $firstTarget->$col) : 0;
                    $monthlyTargetsSum += $val;
                }

                // --- 3. HITUNG ACTUAL DARI DB ---
                $matchedActuals = $actuals->filter(function ($act) use ($kpiItem) {
                    return $act->kpi_item == $kpiItem;
                });

                $sumActualValues = $matchedActuals->sum(function ($item) {
                    return $item->is_valid ? (float) $item->actual : 0;
                });

                $sumPercentageValues = $matchedActuals->sum(function ($item) {
                    return $item->is_valid ? (float) $item->kpi_percentage : 0;
                });

                $totalInvalidWeight = $matchedActuals->sum(function ($item) {
                    return ! $item->is_valid ? (float) $item->invalid_weight : 0;
                });

                // --- 4. LOGIC PERHITUNGAN FINAL (Target & Actual) ---

                // List unit yang menggunakan logika Rata-rata
                $averageUnits  = ['Tgl', 'tgl', '%', 'Kg/Tap', 'Rp/Kg', 'mm', 'M3', 'Hari', 'Jam'];
                $isAverageUnit = in_array($unitItem, $averageUnits);

                if ($isAverageUnit) {
                    // === LOGIC UNIT RATA-RATA (%, Hari, dll) ===
                    // Menggunakan $divisor yang sudah ditentukan di atas (1, 2, 4, atau 12)

                    // Target: Total Sum Target / Divisor
                    // Contoh Annual: Target isi cuma di target_1 (100). Sum = 100. Divisor = 1. Hasil = 100.
                    // Contoh Monthly: Target isi 12 bulan (1200). Sum = 1200. Divisor = 12. Hasil = 100.
                    $totalTarget = $monthlyTargetsSum / $divisor;

                    // Actual: Total Sum Actual / Divisor
                    // Bulan kosong dianggap 0, karena pembagi tetap (1, 2, 4, 12)
                    $totalActual     = $sumActualValues / $divisor;
                    $totalPercentage = $sumPercentageValues / $divisor;

                } else {
                    // === LOGIC UNIT SUM (Rp, Qty, dll) ===
                    // Unit Sum biasanya tidak mempedulikan rata-rata periode, tapi akumulasi total

                    $totalTarget = $monthlyTargetsSum;
                    $totalActual = $sumActualValues;

                    // Percentage Achievement biasanya tetap dirata-rata sesuai periode agar skalanya 0-100%
                    $totalPercentage = $sumPercentageValues / $divisor;
                }

                // --- 5. KALKULASI SKOR ---
                $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $unitItem, $totalPercentage);

                $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                // Capping (Batas Atas)
                if ($convertedCalc > 120 && in_array($unitItem, ['Kg/Tap', 'Rp', 'Rp/Kg', 'Hari', 'Jam'])) {
                    $convertedCalc = 120;
                } elseif ($convertedCalc > 110 && in_array($unitItem, ['Tgl', 'tgl', 'Freq'])) {
                    $convertedCalc = 110;
                } elseif ($convertedCalc > 150 && $unitItem == '%') {
                    $convertedCalc = 150;
                }

                // Hitung Bobot
                $weightRaw = $firstTarget->weighting;
                $weight    = floatval(str_replace('%', '', $weightRaw));

                $totalAchievementWeight = ($convertedCalc * $weight / 100) - $totalInvalidWeight;

                return [
                    'kpi_item'                 => $kpiItem,
                    'unit'                     => $unitItem,
                    'period'                   => $period, // Opsional: kirim info periode ke view
                    'trend'                    => $trendItem,
                    'total_target'             => $totalTarget,
                    'total_actual'             => $totalActual,
                    'percentageCalc'           => $convertedCalc,
                    'weight'                   => $weight,
                    'total_achievement_weight' => $totalAchievementWeight,
                ];
            });

            return view('report-year.employee-report', [
                'title'           => 'Report',
                'desc'            => 'Employee Report',
                'employee'        => $employee,
                'actuals'         => $actuals,
                'targets'         => $targets,
                'totals'          => $totals, // Gunakan variabel ini di view
                'userCreds'       => $userCreds,
                'idParam'         => $id,
                'inactiveTargets' => $inactiveTarget,
            ]);

        } else {
            return view('components/404-page-report');
        }
    }

    public function department($id, Request $request)
    {
        $semester        = $request->query('semester');
        $year            = $request->query('year');
        $departmentCreds = DB::table('departments')->where('id', $id)->first();

        if ($semester && $year) {

            // 1. AMBIL TARGETS
            // Kita select kolom target bulanan dan Period
            $targets = DB::table('department_targets')
                ->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
                ->select(
                    'department_targets.*',
                    'target_units.*', // Asumsi ada kolom unit di target_units atau pakai dari department_targets
                                      // Select Target Bulanan
                    'target_units.target_1', 'target_units.target_2', 'target_units.target_3',
                    'target_units.target_4', 'target_units.target_5', 'target_units.target_6',
                    'target_units.target_7', 'target_units.target_8', 'target_units.target_9',
                    'target_units.target_10', 'target_units.target_11', 'target_units.target_12'
                )
                ->where('department_id', $id)
                ->where('department_targets.is_active', '=', true)
                ->where(DB::raw('YEAR(department_targets.date)'), $year)
                ->get();

            // Target Inactive (Optional)
            $inactiveTarget = DB::table('department_targets')
                ->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
                ->select('department_targets.*', 'target_units.*')
                ->where('department_id', $id)
                ->where('department_targets.is_active', '=', false)
                ->where(DB::raw('YEAR(department_targets.date)'), $year)
                ->get();

            // 2. AMBIL ACTUALS
            $actuals = DB::table('department_actuals')
                ->leftJoin('departments', 'department_actuals.department_id', '=', 'departments.id')

                ->select('department_actuals.date as date', 'department_actuals.department_id as department_id', 'department_actuals.kpi_item', 'department_actuals.kpi_code as kpi_code', 'department_actuals.kpi_weighting', 'department_actuals.kpi_percentage as achievement', 'department_actuals.semester as semester', DB::raw('YEAR(department_actuals.date) as year'), 'department_actuals.target', 'department_actuals.actual', 'department_actuals.kpi_percentage', 'department_actuals.record_file', 'department_actuals.id as department_actual_id', 'department_actuals.status as status', 'departments.name as department', 'department_actuals.trend', 'department_actuals.kpi_unit', 'department_actuals.kpi_unit', 'department_actuals.review_period', 'department_actuals.*')

                ->where('department_actuals.department_id', $id)
            // ->where('department_actuals.semester', $semester) // Opsional: biasanya report tahunan ambil semua data setahun
                ->where(DB::raw('YEAR(department_actuals.date)'), $year)
                ->orderBy(DB::raw('MONTH(department_actuals.date)'))
                ->get();

            // Sum Bobot
            $targetWeightingSum = DB::table('department_targets')
                ->select('weighting')
                ->where('department_id', $id)
                ->where('is_active', true)
                ->where(DB::raw('YEAR(date)'), $year)
                ->get();

            $sumWeighting = $targetWeightingSum->sum(function ($item) {
                return floatval(str_replace('%', '', $item->weighting));
            });

            // --- LOGIC UTAMA (Sama dengan Employee) ---

            // Group by Code atau Indicator (pastikan unik)
            $groupedTargets = $targets->groupBy('code');

            $totals = $groupedTargets->map(function ($targetGroup) use ($actuals) {
                $firstTarget = $targetGroup->first();

                $unitItem  = $firstTarget->unit; // Ambil unit
                $kpiCode   = $firstTarget->code; // Kunci pencocokan dengan actual
                $trendItem = $firstTarget->trend;
                $period    = $firstTarget->period ?? 'Monthly'; // Default Monthly jika null

                // 1. TENTUKAN PEMBAGI (DIVISOR)
                $divisor    = 12;
                $periodName = strtolower($period);

                if ($periodName == 'annual') {
                    $divisor = 1;
                } elseif ($periodName == 'semester') {
                    $divisor = 2;
                } elseif ($periodName == 'quarter') {
                    $divisor = 4;
                } elseif ($periodName == 'monthly') {
                    $divisor = 12;
                }

                // 2. HITUNG TARGET (Loop target_1 ... target_12)
                $monthlyTargetsSum = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $col                = 'target_' . $i;
                    $val                = isset($firstTarget->$col) ? (float) str_replace(',', '', $firstTarget->$col) : 0;
                    $monthlyTargetsSum += $val;
                }

                // 3. LOGIC ACTUAL (Matching Data)
                // Filter actuals yang kpi_code-nya sama
                $matchedActuals = $actuals->filter(function ($act) use ($kpiCode) {
                    return $act->kpi_code == $kpiCode;
                });

                $sumActualValues = $matchedActuals->sum(function ($item) {
                    return $item->is_valid ? (float) $item->actual : 0;
                });

                $sumPercentageValues = $matchedActuals->sum(function ($item) {
                    return $item->is_valid ? (float) $item->kpi_percentage : 0;
                });

                $totalInvalidWeight = $matchedActuals->sum(function ($item) {
                    return ! $item->is_valid ? (float) $item->invalid_weight : 0;
                });

                // 4. FINAL CALCULATION (Apply Divisor rule)
                $averageUnits  = ['Tgl', 'tgl', '%', 'Kg/Tap', 'Rp/Kg', 'mm', 'M3', 'Hari', 'Jam'];
                $isAverageUnit = in_array($unitItem, $averageUnits);

                if ($isAverageUnit) {
                    // Unit Rata-rata: Dibagi Divisor (1, 2, 4, 12)
                    $totalTarget = $monthlyTargetsSum / $divisor;
                    // Actual dibagi divisor (Bulan kosong dianggap 0)
                    $totalActual     = $sumActualValues / $divisor;
                    $totalPercentage = $sumPercentageValues / $divisor;
                } else {
                    // Unit Sum: Akumulasi
                    $totalTarget = $monthlyTargetsSum;
                    $totalActual = $sumActualValues;
                    // Percentage biasanya tetap dirata-rata agar range 0-100%
                    $totalPercentage = $sumPercentageValues / $divisor;
                }

                // 5. Calculate Score
                // Gunakan $targetVal sebagai totalTarget jika logic sebelumnya menggunakan single value,
                // tapi di sini kita sudah hitung $totalTarget yang benar dari breakdown.
                $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $unitItem, $totalPercentage);

                $convertedCalc = floatval(str_replace('%', '', $percentageCalc));

                // Capping
                if ($convertedCalc > 120 && in_array($unitItem, ['Kg/Tap', 'Rp', 'Rp/Kg', 'Hari', 'Jam'])) {
                    $convertedCalc = 120;
                } elseif ($convertedCalc > 110 && in_array($unitItem, ['Tgl', 'tgl', 'Freq'])) {
                    $convertedCalc = 110;
                } elseif ($convertedCalc > 150 && $unitItem == '%') {
                    $convertedCalc = 150;
                }

                // Weight
                $weightRaw = $firstTarget->weighting;
                $weight    = floatval(str_replace('%', '', $weightRaw));

                $totalAchievementWeight = ($convertedCalc * $weight / 100) - $totalInvalidWeight;

                // Return structure (sesuaikan dengan view department-report)
                return [
                    'kpi_code'                 => $kpiCode,
                    'indicator'                => $firstTarget->indicator ?? $kpiCode,
                    'unit'                     => $unitItem,
                    'period'                   => $period,
                    'trend'                    => $trendItem,
                    'total_target'             => $totalTarget,
                    'total_actual'             => $totalActual,
                    'weight'                   => $weight,
                    'percentageCalc'           => $convertedCalc,
                    'total_achievement_weight' => $totalAchievementWeight,
                ];
            });

            return view('report-year.department-report', [
                'title'           => 'Report',
                'desc'            => 'Summary KPI Dept',
                'actuals'         => $actuals,
                'targets'         => $targets,
                'totals'          => $totals,
                'sumWeighting'    => $sumWeighting,
                'departmentCreds' => $departmentCreds,
                'idParam'         => $id,
                'inactiveTargets' => $inactiveTarget,
            ]);

        } else {
            return view('components/404-page-report');
        }
    }

    public function summaryDept(Request $request)
    {
        $department = $request->query('department');
        $yearToShow = $request->query('year');
        $status     = $request->query('status');
        $allDept    = Department::all();
        $allStatus  = Employee::select('status')->distinct()->get();

        if ($yearToShow) {
            // 1. FILTER EMPLOYEES
            $query = DB::table('employees')
                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                ->select('departments.name as dept', 'employees.name as name', 'employees.nik', 'employees.occupation', 'employees.id as employee_id', 'department_id')
                ->where('employees.is_active', '=', 1);

            if ($department) {
                $query->whereIn('departments.id', (array) $department);
            }
            if ($status) {
                $query->whereIn('employees.status', (array) $status);
            }

            $employees = $query->paginate(35)->appends($request->all());

            // 2. MAPPING IDs
            $employeeIds   = $employees->pluck('employee_id');
            $departmentIds = $employees->pluck('department_id')->unique();
            $empDeptMap    = $employees->pluck('department_id', 'employee_id');

            // Logic Department ID Grouping (Director/HO Logic)
            $departmentIdsToCheck = $departmentIds->toArray();
            if (in_array(11, $departmentIdsToCheck)) {
                $departmentIdsToCheck = array_merge($departmentIdsToCheck, [13, 14, 15, 16, 17, 18]);
            }
            if (in_array(21, $departmentIdsToCheck)) {
                $departmentIdsToCheck = array_merge($departmentIdsToCheck, [13, 14, 15]);
            }
            if (in_array(22, $departmentIdsToCheck)) {
                $departmentIdsToCheck = array_merge($departmentIdsToCheck, [16, 17, 18]);
            }
            $departmentIdsToCheck = array_unique($departmentIdsToCheck);

            // 3. FETCH TARGETS
            // NOTE: Tambahkan 'target_units.period' agar logika Employee bisa jalan (butuh Annual/Semester/dll)
            $empTargets = DB::table('targets')
                ->leftJoin('target_units', 'targets.target_unit_id', '=', 'target_units.id')
                ->select('targets.*', 'target_units.*',
                    'target_units.target_1', 'target_units.target_2', 'target_units.target_3', 'target_units.target_4',
                    'target_units.target_5', 'target_units.target_6', 'target_units.target_7', 'target_units.target_8',
                    'target_units.target_9', 'target_units.target_10', 'target_units.target_11', 'target_units.target_12'
                )
                ->whereIn('employee_id', $employeeIds)
                ->where(DB::raw('YEAR(targets.date)'), $yearToShow)
                ->get();

            $deptTargets = DB::table('department_targets')
                ->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
                ->select('department_targets.*', 'target_units.*',
                    'target_units.target_1', 'target_units.target_2', 'target_units.target_3', 'target_units.target_4',
                    'target_units.target_5', 'target_units.target_6', 'target_units.target_7', 'target_units.target_8',
                    'target_units.target_9', 'target_units.target_10', 'target_units.target_11', 'target_units.target_12'
                )
                ->whereIn('department_id', $departmentIdsToCheck)
                ->where(DB::raw('YEAR(department_targets.date)'), $yearToShow)
                ->get();

            // 4. FETCH ACTUALS
            $empActuals = DB::table('actuals')
                ->whereIn('employee_id', $employeeIds)
                ->where(DB::raw('YEAR(date)'), $yearToShow)
                ->get();

            $deptActuals = DB::table('department_actuals')
                ->whereIn('department_id', $departmentIdsToCheck)
                ->where(DB::raw('YEAR(date)'), $yearToShow)
                ->get();

            // 5. HELPER FUNCTION: CALCULATE ANNUAL SCORE
            $calculateAnnualScore = function ($targets, $actuals, $isDept = false) {
                $groupByKey = $isDept ? 'department_id' : 'employee_id';

                return $targets->groupBy($groupByKey)->map(function ($targetGroup) use ($actuals, $isDept) {
                    return $targetGroup->groupBy($isDept ? 'code' : 'indicator')->map(function ($subGroup) use ($actuals, $isDept) {

                        $firstTarget = $subGroup->first();
                        $currentId   = $isDept ? $firstTarget->department_id : $firstTarget->employee_id;

                        $unitItem  = $firstTarget->unit_name ?? $firstTarget->unit ?? '';
                        $trendItem = $firstTarget->trend;
                        $period    = $firstTarget->period ?? 'Monthly';
                        $kpiCode   = $firstTarget->code;
                        $kpiItem   = $firstTarget->indicator;

                        // --- Hitung Total Target (Sum 1-12) ---
                        $targetSum   = 0;
                        $targetCount = 0;
                        for ($i = 1; $i <= 12; $i++) {
                            $col        = 'target_' . $i;
                            $val        = isset($firstTarget->$col) ? (float) str_replace(',', '', $firstTarget->$col) : 0;
                            $targetSum += $val;
                            if ($val > 0) {
                                $targetCount++;
                            }

                        }

                        // --- Filter Actuals (Strict ID Match) ---
                        $matchedActuals = $actuals->filter(function ($act) use ($kpiCode, $kpiItem, $isDept, $currentId) {
                            $idMatch   = $isDept ? ($act->department_id == $currentId) : ($act->employee_id == $currentId);
                            $codeMatch = $isDept ? ($act->kpi_code == $kpiCode) : ($act->kpi_item == $kpiItem);
                            return $idMatch && $codeMatch;
                        });

                        $sumActualValues     = $matchedActuals->sum(fn($item) => $item->is_valid ? (float) $item->actual : 0);
                        $sumPercentageValues = $matchedActuals->sum(fn($item) => $item->is_valid ? (float) $item->kpi_percentage : 0);
                        $actualCountValid    = $matchedActuals->where('is_valid', 1)->count();
                        $totalInvalidWeight  = $matchedActuals->sum(fn($item) => ! $item->is_valid ? (float) $item->invalid_weight : 0);

                        // --- Logic Calculation ---
                        $averageUnits = ['Tgl', 'tgl', '%', 'Kg/Tap', 'Rp/Kg', 'mm', 'M3', 'Hari', 'Jam'];
                        if ($isDept) {
                            $averageUnits[] = 'Freq "0"';
                        }
                        $isAverageUnit = in_array($unitItem, $averageUnits);

                        $convertedCalc = 0;

                        // ==========================================
                        //  PERCABANGAN LOGIKA: DEPT vs EMPLOYEE
                        // ==========================================
                        if ($isDept) {
                            // === LOGIKA DEPARTMENT (Sesuai request Anda: "KPI deptnya sudah pas") ===
                            // Menggunakan logic Dynamic Count ($actualCountValid)

                            if ($isAverageUnit) {
                                if ($actualCountValid > 0) {
                                    $rawPercentage = $sumPercentageValues / $actualCountValid;
                                } else {
                                    $rawPercentage = 0;
                                }
                                $convertedCalc = $rawPercentage;
                            } else {
                                // Sum Unit Dept
                                $totalTarget = $targetSum;
                                $totalActual = $sumActualValues;

                                if ($totalTarget > 0) {
                                    $calcRes       = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $unitItem, 0);
                                    $convertedCalc = floatval(str_replace('%', '', $calcRes));
                                } else {
                                    $convertedCalc = ($totalActual > 0) ? 100 : 0;
                                }
                            }

                        } else {
                            // === LOGIKA EMPLOYEE (Sesuai request Anda: "Jiplak function SHOW") ===
                            // Menggunakan logic Fixed Divisor (1, 2, 4, 12)

                                              // 1. Tentukan Divisor
                            $divisor    = 12; // Default Monthly
                            $periodName = strtolower($period);
                            if ($periodName == 'annual') {
                                $divisor = 1;
                            } elseif ($periodName == 'semester') {
                                $divisor = 2;
                            } elseif ($periodName == 'quarter') {
                                $divisor = 4;
                            }

                            if ($isAverageUnit) {
                                // Unit Rata-rata: Dibagi Divisor (1, 2, 4, 12)
                                // Note: $targetSum disini dipakai untuk TotalTarget perhitungan calculation()
                                $totalTarget     = $targetSum / $divisor;
                                $totalActual     = $sumActualValues / $divisor;
                                $totalPercentage = $sumPercentageValues / $divisor;
                            } else {
                                // Unit Sum: Akumulasi
                                $totalTarget     = $targetSum;
                                $totalActual     = $sumActualValues;
                                $totalPercentage = $sumPercentageValues / $divisor;
                            }

                            // 2. Kalkulasi
                            $percentageCalc = $this->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $unitItem, $totalPercentage);
                            $convertedCalc  = floatval(str_replace('%', '', $percentageCalc));
                        }

                        // --- Final Capping & Weighting (Sama untuk keduanya) ---
                        if ($convertedCalc > 120 && in_array($unitItem, ['Kg/Tap', 'Rp', 'Rp/Kg', 'Hari', 'Jam'])) {
                            $convertedCalc = 120;
                        } elseif ($convertedCalc > 110 && in_array($unitItem, ['Tgl', 'tgl', 'Freq'])) {
                            $convertedCalc = 110;
                        } elseif ($convertedCalc > 150 && $unitItem == '%') {
                            $convertedCalc = 150;
                        }

                        $weight                 = floatval(str_replace('%', '', $firstTarget->weighting));
                        $totalAchievementWeight = ($convertedCalc * $weight / 100) - $totalInvalidWeight;

                        return ['total_achievement_weight' => $totalAchievementWeight];
                    });
                });
            };

            // 6. EXECUTE CALCULATION
            $activeEmpTargets  = $empTargets->where('is_active', 1);
            $activeDeptTargets = $deptTargets->where('is_active', 1);

            $annualEmpGroup  = $calculateAnnualScore($activeEmpTargets, $empActuals, false);
            $annualDeptGroup = $calculateAnnualScore($activeDeptTargets, $deptActuals, true);

            // Inactive
            $inactiveEmpTargets      = $empTargets->where('is_active', 0);
            $inactiveDeptTargets     = $deptTargets->where('is_active', 0);
            $annualInactiveEmpGroup  = $calculateAnnualScore($inactiveEmpTargets, $empActuals, false);
            $annualInactiveDeptGroup = $calculateAnnualScore($inactiveDeptTargets, $deptActuals, true);

            // 7. SUMMATION
            $sumEmpAnnual  = $annualEmpGroup->mapWithKeys(fn($group, $id) => [$id => $group->sum('total_achievement_weight')]);
            $sumDeptAnnual = $annualDeptGroup->mapWithKeys(fn($group, $id) => [$id => $group->sum('total_achievement_weight')]);

            // 8. FINAL TOTAL CALCULATION
            $totalSumAnnual = $sumEmpAnnual->map(function ($empVal, $empId) use ($sumDeptAnnual, $empDeptMap) {
                $deptId  = $empDeptMap[$empId] ?? null;
                $deptVal = 0;

                if ($deptId) {
                    if ($deptId == 11) {
                        $deptVal = collect([13, 14, 15, 16, 17, 18])->avg(fn($id) => $sumDeptAnnual[$id] ?? 0);
                    } elseif ($deptId == 21) {
                        $deptVal = collect([13, 14, 15])->avg(fn($id) => $sumDeptAnnual[$id] ?? 0);
                    } elseif ($deptId == 22) {
                        $deptVal = collect([16, 17, 18])->avg(fn($id) => $sumDeptAnnual[$id] ?? 0);
                    } else {
                        $deptVal = $sumDeptAnnual[$deptId] ?? 0;
                    }
                }
                return ($empVal * 0.7) + ($deptVal * 0.3);
            });

            // Inactive Sums
            $sumInactiveEmpAnnual   = $annualInactiveEmpGroup->mapWithKeys(fn($g, $id) => [$id => $g->sum('total_achievement_weight')]);
            $sumInactiveDeptAnnual  = $annualInactiveDeptGroup->mapWithKeys(fn($g, $id) => [$id => $g->sum('total_achievement_weight')]);

            $totalSumInactiveAnnual = $sumInactiveEmpAnnual->map(function ($empVal, $empId) use ($sumInactiveDeptAnnual, $empDeptMap) {
                $deptId  = $empDeptMap[$empId] ?? null;
                $deptVal = 0;
                if ($deptId) {
                    if ($deptId == 11) {
                        $deptVal = collect([13, 14, 15, 16, 17, 18])->avg(fn($id) => $sumInactiveDeptAnnual[$id] ?? 0);
                    } elseif ($deptId == 21) {
                        $deptVal = collect([13, 14, 15])->avg(fn($id) => $sumInactiveDeptAnnual[$id] ?? 0);
                    } elseif ($deptId == 22) {
                        $deptVal = collect([16, 17, 18])->avg(fn($id) => $sumInactiveDeptAnnual[$id] ?? 0);
                    } else {
                        $deptVal = $sumInactiveDeptAnnual[$deptId] ?? 0;
                    }
                }
                return ($empVal * 0.7) + ($deptVal * 0.3);
            });

            $inactiveTargetEmployees = $employees->whereIn('employee_id', $inactiveEmpTargets->pluck('employee_id')->unique());

            return view('report-year.summary-department-report', [
                'title'                   => 'Report',
                'desc'                    => 'Annual Department Report',
                'allDept'                 => $allDept,
                'allOccupation'           => $allStatus,
                'employees'               => $employees,
                'sumEmpAnnual'            => $sumEmpAnnual,
                'sumDeptAnnual'           => $sumDeptAnnual,
                'totalSumAnnual'          => $totalSumAnnual,
                'inactiveTargetEmployees' => $inactiveTargetEmployees,
                'sumInactiveEmpAnnual'    => $sumInactiveEmpAnnual,
                'sumInactiveDeptAnnual'   => $sumInactiveDeptAnnual,
                'totalSumInactiveAnnual'  => $totalSumInactiveAnnual,
            ]);

        } else {
            return view('components/404-page');
        }
    }

    public function showFile(Request $request)
    {
        $month    = $request->query('month');
        $actualId = $request->query('actual_id');

        $pdfUrls = Actual::whereMonth('date', $month)
            ->where('id', $actualId)
            ->get(['id', 'record_file', 'kpi_code', 'kpi_item', 'status', 'comment', 'target', 'actual', 'kpi_percentage'])
            ->toArray();

        return response()->json($pdfUrls);
    }

    public function exportSummaryDept(Request $request)
    {
        $year       = $request->query('year', now()->year);
        $department = $request->query('department');
        $status     = $request->query('status');

        return Excel::download(new SummaryDeptAnnualExport($year, $department, $status), 'summary-department-annual-' . $year . '.xlsx');
    }

    public function showFileDept(Request $request)
    {
        $month    = $request->query('month');
        $actualId = $request->query('actual_id');

        $pdfUrls = DepartmentActual::whereMonth('date', $month)
            ->where('id', $actualId)
            ->get(['id', 'record_file', 'kpi_code', 'kpi_item', 'status', 'comment', 'target', 'actual', 'kpi_percentage'])
            ->toArray();

        return response()->json($pdfUrls);
    }

    public function indexDeptTargetReport(Request $request)
    {
        $year    = $request->query('year');
        $targets = DB::table('department_targets')->select('department_targets.indicator', )->whereYear('department_targets.date', '=', $year)
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

    public function setDataInvalid(Request $request)
    {
        $actualId      = $request->input('actual_id');
        $invalidReason = $request->input('invalid_reason');

        $reviewPeriod = DB::table('actuals')->where('id', $actualId)->value('review_period');

        $mapPeriod = [
            'Monthly'        => 12,
            'Quarterly'      => 4,
            'Semesterly'     => 2,
            'Yearly'         => 1,
            'Every 2 Months' => 6,
        ];

        $divider = $mapPeriod[$reviewPeriod] ?? 1;

        $kpiWeighting = DB::table('actuals')->where('id', $actualId)->value('kpi_weighting');

        $setInvalidImpact = floatval($kpiWeighting) / $divider;

        // Update the status of the actual record
        DB::table('actuals')
            ->where('id', $actualId)
            ->update(['is_valid' => 0,
                'status'             => 'Invalid',
                'invalid_reason'     => $invalidReason,
                'invalid_weight'     => $setInvalidImpact,
                'actual'             => 0,
                'kpi_percentage'     => 0]);

        // Return a success response
        return back()->with('success', 'Data has been set to invalid successfully.');
    }

    public function setDataInvalidDept(Request $request)
    {
        $actualId      = $request->input('department_actual_id');
        $invalidReason = $request->input('invalid_reason');

        // Update the status of the actual record
        DB::table('department_actuals')
            ->where('id', $actualId)
            ->update(['is_valid' => 0, 'status' => 'Invalid', 'invalid_reason' => $invalidReason]);

        // Return a success response
        return back()->with('success', 'Data has been set to invalid successfully.');
    }
}
