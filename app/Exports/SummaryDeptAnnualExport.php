<?php
namespace App\Exports;

use App\Models\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SummaryDeptAnnualExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $year;
    protected $department;
    protected $status;

    public function __construct($year, $department, $status)
    {
        $this->year       = $year;
        $this->department = $department;
        $this->status     = $status;
    }

    public function view(): View
    {
        $yearToShow = $this->year;
        $department = $this->department;
        $status     = $this->status;

        // 1. FILTER EMPLOYEES (Gunakan GET, bukan PAGINATE)
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

        // AMBIL SEMUA DATA
        $employees = $query->get();

        // 2. MAPPING IDs
        $employeeIds   = $employees->pluck('employee_id');
        $departmentIds = $employees->pluck('department_id')->unique();
        $empDeptMap    = $employees->pluck('department_id', 'employee_id');

        // Logic Department ID Grouping
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

        // 5. CALCULATE LOGIC (Sama persis dengan Controller)
        $calculateAnnualScore = function ($targets, $actuals, $isDept = false) {
            $groupByKey = $isDept ? 'department_id' : 'employee_id';
            return $targets->groupBy($groupByKey)->map(function ($targetGroup) use ($actuals, $isDept) {
                return $targetGroup->groupBy($isDept ? 'code' : 'indicator')->map(function ($subGroup) use ($actuals, $isDept) {

                    $firstTarget = $subGroup->first();
                    $currentId   = $isDept ? $firstTarget->department_id : $firstTarget->employee_id;
                    $unitItem    = $firstTarget->unit_name ?? $firstTarget->unit ?? '';
                    $trendItem   = $firstTarget->trend;
                    $period      = $firstTarget->period ?? 'Monthly';
                    $kpiCode     = $firstTarget->code;
                    $kpiItem     = $firstTarget->indicator;

                    $targetSum = 0;
                    for ($i = 1; $i <= 12; $i++) {
                        $col        = 'target_' . $i;
                        $val        = isset($firstTarget->$col) ? (float) str_replace(',', '', $firstTarget->$col) : 0;
                        $targetSum += $val;
                    }

                    $matchedActuals = $actuals->filter(function ($act) use ($kpiCode, $kpiItem, $isDept, $currentId) {
                        $idMatch   = $isDept ? ($act->department_id == $currentId) : ($act->employee_id == $currentId);
                        $codeMatch = $isDept ? ($act->kpi_code == $kpiCode) : ($act->kpi_item == $kpiItem);
                        return $idMatch && $codeMatch;
                    });

                    $sumActualValues     = $matchedActuals->sum(fn($item) => $item->is_valid ? (float) $item->actual : 0);
                    $sumPercentageValues = $matchedActuals->sum(fn($item) => $item->is_valid ? (float) $item->kpi_percentage : 0);
                    $actualCountValid    = $matchedActuals->where('is_valid', 1)->count();
                    $totalInvalidWeight  = $matchedActuals->sum(fn($item) => ! $item->is_valid ? (float) $item->invalid_weight : 0);

                    $averageUnits  = ['Tgl', 'tgl', '%', 'Kg/Tap', 'Rp/Kg', 'mm', 'M3', 'Hari', 'Jam', 'Freq "0"'];
                    $isAverageUnit = in_array($unitItem, $averageUnits);
                    $convertedCalc = 0;

                    // --- LOGIC PERCABANGAN (COPY FROM CONTROLLER) ---
                    if ($isDept) {
                        // LOGIC DEPARTMENT (Dynamic Count)
                        if ($isAverageUnit) {
                            $convertedCalc = ($actualCountValid > 0) ? ($sumPercentageValues / $actualCountValid) : 0;
                        } else {
                            $totalTarget = $targetSum;
                            $totalActual = $sumActualValues;
                            if ($totalTarget > 0) {
                                $calcRes       = app('App\Http\Controllers\ReportYearController')->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $unitItem, 0);
                                $convertedCalc = floatval(str_replace('%', '', $calcRes));
                            } else {
                                $convertedCalc = ($totalActual > 0) ? 100 : 0;
                            }
                        }
                    } else {
                        // LOGIC EMPLOYEE (Fixed Divisor)
                        $divisor    = 12;
                        $periodName = strtolower($period);
                        if ($periodName == 'annual') {
                            $divisor = 1;
                        } elseif ($periodName == 'semester') {
                            $divisor = 2;
                        } elseif ($periodName == 'quarter') {
                            $divisor = 4;
                        }

                        if ($isAverageUnit) {
                            $totalTarget     = $targetSum / $divisor;
                            $totalActual     = $sumActualValues / $divisor;
                            $totalPercentage = $sumPercentageValues / $divisor;
                        } else {
                            $totalTarget     = $targetSum;
                            $totalActual     = $sumActualValues;
                            $totalPercentage = $sumPercentageValues / $divisor;
                        }
                        $percentageCalc = app('App\Http\Controllers\ReportYearController')->calculation($totalTarget, $totalTarget, $totalActual, $trendItem, $unitItem, $totalPercentage);
                        $convertedCalc  = floatval(str_replace('%', '', $percentageCalc));
                    }

                    // Capping
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

        // 6. EXECUTE
        $activeEmpTargets  = $empTargets->where('is_active', 1);
        $activeDeptTargets = $deptTargets->where('is_active', 1);
        $annualEmpGroup    = $calculateAnnualScore($activeEmpTargets, $empActuals, false);
        $annualDeptGroup   = $calculateAnnualScore($activeDeptTargets, $deptActuals, true);

        $inactiveEmpTargets      = $empTargets->where('is_active', 0);
        $inactiveDeptTargets     = $deptTargets->where('is_active', 0);
        $annualInactiveEmpGroup  = $calculateAnnualScore($inactiveEmpTargets, $empActuals, false);
        $annualInactiveDeptGroup = $calculateAnnualScore($inactiveDeptTargets, $deptActuals, true);

        // 7. SUMMATION
        $sumEmpAnnual          = $annualEmpGroup->mapWithKeys(fn($group, $id) => [$id => $group->sum('total_achievement_weight')]);
        $sumDeptAnnual         = $annualDeptGroup->mapWithKeys(fn($group, $id) => [$id => $group->sum('total_achievement_weight')]);
        $sumInactiveEmpAnnual  = $annualInactiveEmpGroup->mapWithKeys(fn($g, $id) => [$id => $g->sum('total_achievement_weight')]);
        $sumInactiveDeptAnnual = $annualInactiveDeptGroup->mapWithKeys(fn($g, $id) => [$id => $g->sum('total_achievement_weight')]);

        $inactiveTargetEmployees = $employees->whereIn('employee_id', $inactiveEmpTargets->pluck('employee_id')->unique());

        return view('report-year.summary-dept-annual-excel', [
            'employees'               => $employees,
            'sumEmpAnnual'            => $sumEmpAnnual,
            'sumDeptAnnual'           => $sumDeptAnnual,
            'sumInactiveEmpAnnual'    => $sumInactiveEmpAnnual,
            'sumInactiveDeptAnnual'   => $sumInactiveDeptAnnual,
            'inactiveTargetEmployees' => $inactiveTargetEmployees,
            'year'                    => $yearToShow,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
                                                                                                                                                             // Style Header Table 1 (Row 1 & 2)
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1D4ED8']]], // Blue-700
            2 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1D4ED8']]],
        ];
    }
}
