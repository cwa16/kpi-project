<?php
namespace App\Http\Controllers;

use App\Jobs\ApproveEmail;
use App\Jobs\ReminderApproveEmail;
use App\Jobs\ReminderCheck1Email;
use App\Jobs\ReminderCheck2Email;
use App\Jobs\ReminderInputEmail;
use App\Models\Actual;
use App\Models\Department;
use App\Models\DepartmentActual;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ActualController extends Controller
{
    public function show(Request $request)
    {
        $employeeID = $request->query('employee');
        $employee   = Employee::find($employeeID);
        $year       = $request->query('year');

        // Ambil data targets
        $targets = DB::table('targets')
            ->select('id', 'code', 'indicator', 'period', 'employee_id')
            ->where('employee_id', $employeeID)
            ->where('targets.is_active', 1)
            ->where(DB::raw('YEAR(date)'), '=', $year)
            ->get();

        // Ambil data actuals
        $actuals = DB::table('actuals')
            ->leftJoin('employees', 'actuals.employee_id', '=', 'employees.id')
            ->leftJoin('targets', 'actuals.kpi_code', '=', 'targets.code')
            ->leftJoin('target_units', 'target_units.id', '=', 'targets.target_unit_id')
            ->select('actuals.kpi_code as kpi_code', 'targets.code as code', 'actuals.date as actual_date', 'targets.date as target_date', 'targets.indicator as indicator', 'actuals.kpi_item', 'actuals.status')
        // ->where(DB::raw('MONTH(actuals.date)'), '<=', $now->month)
            ->where('actuals.employee_id', $employeeID)
            ->where(DB::raw('YEAR(actuals.date)'), '=', $year)
            ->get();

        $targetUnits1 = DB::table('target_units')->leftJoin('targets', 'targets.target_unit_id', '=', 'target_units.id')->select('target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'target_6', 'targets.id as target_id', 'targets.date as month')->where('employee_id', '=', $employeeID)->whereYear('targets.date', '=', $year)->get();

        $targetUnits2 = DB::table('target_units')->leftJoin('targets', 'targets.target_unit_id', '=', 'target_units.id')->select('target_7', 'target_8', 'target_9', 'target_10', 'target_11', 'target_12', 'targets.id as target_id', 'targets.date as month')->where('employee_id', '=', $employeeID)->whereYear('targets.date', '=', $year)->get();

        // dd($actuals);
        return view('actual.input-actual-employee', [
            'title'        => 'Input Data Realisasi',
            'desc'         => 'Monitoring KPI',
            'employee'     => $employee,
            'targets'      => $targets,
            'actuals'      => $actuals,
            'targetUnits1' => $targetUnits1,
            'targetUnits2' => $targetUnits2,
        ]);
    }

    public function showDept(Request $request)
    {
        $departmentID = $request->query('department');
        $department   = Department::find($departmentID);
        $year         = $request->query('year');

        // Ambil data targets
        $targets = DB::table('department_targets')
            ->where('department_targets.department_id', '=', $departmentID)
            ->where('department_targets.is_active', 1)
            ->where(DB::raw('YEAR(department_targets.date)'), '=', $year)
            ->get();

        // Ambil data actuals
        $actuals = DB::table('department_actuals')
            ->leftJoin('departments', 'departments.id', '=', 'department_actuals.department_id')
            ->leftJoin('department_targets', 'department_actuals.kpi_code', '=', 'department_targets.code')
            ->select('department_actuals.kpi_code as kpi_code', 'department_targets.code as code', 'department_actuals.date as actual_date', 'department_targets.date as target_date', 'department_targets.indicator as indicator', 'department_actuals.status')
        // ->where(DB::raw('MONTH(actuals.date)'), '<=', $now->month)
            ->where('department_actuals.department_id', '=', $departmentID)
            ->where(DB::raw('YEAR(department_actuals.date)'), '=', $year)
            ->get();

        $targetUnits1 = DB::table('target_units')->leftJoin('department_targets', 'department_targets.target_unit_id', '=', 'target_units.id')->select('target_1', 'target_2', 'target_3', 'target_4', 'target_5', 'target_6', 'department_targets.id as target_id', 'department_targets.date as month')->where('department_id', '=', $departmentID)->whereYear('department_targets.date', '=', $year)->get();

        $targetUnits2 = DB::table('target_units')->leftJoin('department_targets', 'department_targets.target_unit_id', '=', 'target_units.id')->select('target_7', 'target_8', 'target_9', 'target_10', 'target_11', 'target_12', 'department_targets.id as target_id', 'department_targets.date as month')->where('department_id', '=', $departmentID)->whereYear('department_targets.date', '=', $year)->get();

        return view('actual.input-actual-department-details', [
            'title'        => 'Input Data Realisasi',
            'desc'         => 'Monitoring KPI',
            'departments'  => $department,
            'targets'      => $targets,
            'actuals'      => $actuals,
            'targetUnits1' => $targetUnits1,
            'targetUnits2' => $targetUnits2,
        ]);
    }

    public function department(Request $request)
    {
        $department = $request->query('department');
        $employee   = $request->query('employee');
        $user       = Auth::user();
        $role       = $user->role;
        $email      = $user->email;
        $authDept   = $user->department_id;

        if ($role == 'Checker Div 1' || $role == 'Checker Div 2') {
            $dept    = ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F'];
            $allDept = DB::table('departments')->whereIn('name', $dept)->get();
        } elseif ($role == 'FAD') {
            $dept    = ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'FAD', 'FSD'];
            $allDept = DB::table('departments')->whereIn('name', $dept)->get();
        } elseif ($role == 'Checker WS') {
            $dept    = 'Workshop';
            $allDept = DB::table('departments')->where('name', '=', $dept)->get();
        } elseif ($role == 'Checker Factory') {
            $dept    = 'Factory';
            $allDept = DB::table('departments')->where('name', '=', $dept)->get();
        } elseif ($email == 'tabrani@bskp.co.id' || $email == 'siswantoko@bskp.co.id') {
            $dept    = ['Sub Div A', 'Sub Div B', 'Sub Div C', 'Sub Div D', 'Sub Div E', 'Sub Div F', 'Div 1', 'Div 2'];
            $allDept = DB::table('departments')->whereIn('name', $dept)->get();
        } elseif ($email == 'hendi@bskp.co.id') {
            $dept    = ['Accounting', 'Finance'];
            $allDept = DB::table('departments')->whereIn('name', $dept)->get();
        } elseif ($role == 'Checker 1') {
            $allDept = DB::table('departments')->where('departments.id', $authDept)->get();
        } else {
            $allDept = DB::table('departments')->get();
        }

        if ($department && $employee) {

            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')
                ->where('employees.id', $employee)
                ->where('departments.id', $department)
                ->where('employees.is_active', 1)
                ->get();

            return view('actual.input-actual-department', [
                'title'       => 'Input Data Realisasi',
                'desc'        => 'List Karyawan',
                'departments' => $departments,
                'allDept'     => $allDept,
            ]);
        } else if ($department) {
            // $userDepartmentID = Auth::user()->department_id;
            // if ($department != $userDepartmentID) {
            //     abort(403, 'Unauthorized');
            // }

           $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')

                ->where('employees.is_active', 1)
                ->where('departments.id', $department)
                ->get();

            return view('actual.input-actual-department', [
                'title'       => 'Input Data Realisasi',
                'desc'        => 'List Karyawan',
                'departments' => $departments,
                'allDept'     => $allDept,
            ]);
        } elseif ($employee) {
            $userDepartmentID = Auth::user()->id;
            if ($employee != $userDepartmentID) {
                abort(403, 'Unauthorized');
            }
            $departments = DB::table('departments')
                ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
                ->select('employees.id as employee_id', 'employees.nik as nik', 'employees.name as employee', 'employees.occupation as occupation', 'departments.name as department', 'departments.id as department_id')
                ->where('employees.id', $employee)
                ->where('employees.is_active', 1)
                ->get();

            return view('actual.input-actual-department', [
                'title'       => 'Input Data Realisasi',
                'desc'        => 'List Karyawan',
                'departments' => $departments,
                'allDept'     => $allDept,
            ]);
        } else {
            return view('components/404-page');
        }
    }

    public function edit($id, Request $request)
    {

        $year     = $request->query('year');
        $employee = Employee::find($id);
        $targets  = DB::table('targets')->leftJoin('target_units', 'target_units.id', '=', 'targets.target_unit_id')
            ->leftJoin('employees', 'employees.id', '=', 'targets.employee_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('targets.id', '=', $id)
            ->where(DB::raw('YEAR(targets.date)'), $year)
            ->select('targets.id', 'targets.employee_id', 'targets.code', 'targets.indicator', 'targets.calculation', 'targets.period', 'targets.unit', 'targets.supporting_document', 'targets.weighting', 'targets.detail', 'targets.trend', 'target_units.id as target_unit_id', 'employees.nik as nik', 'employees.occupation as occupation', 'employees.name as employee', 'departments.name as department', 'target_1 as target_unit_1', 'target_2 as target_unit_2', 'target_3 as target_unit_3', 'target_4 as target_unit_4', 'target_5 as target_unit_5', 'target_6 as target_unit_6', 'target_7 as target_unit_7', 'target_8 as target_unit_8', 'target_9 as target_unit_9', 'target_10 as target_unit_10', 'target_11 as target_unit_11', 'target_12 as target_unit_12')
            ->get();

        // dd($targets);

        // dd($targets->toSql());
        return view('actual.input-actual-achievement', [
            'title'     => 'Input Data Realisasi',
            'desc'      => 'Achievement',
            'employees' => $employee,
            'targets'   => $targets,
        ]);
    }

    public function editDept($id, Request $request)
    {
        $department = Department::find($id);
        $year       = $request->query('year');

        $targets = DB::table('department_targets')
            ->leftJoin('departments', 'departments.id', '=', 'department_targets.department_id')
            ->leftJoin('target_units', 'department_targets.target_unit_id', '=', 'target_units.id')
            ->select('department_targets.id', 'department_targets.department_id', 'department_targets.trend', 'department_targets.target_unit_id', 'departments.name as department', 'department_targets.code', 'department_targets.indicator', 'department_targets.calculation', 'department_targets.supporting_document', 'department_targets.period', 'department_targets.date', 'department_targets.weighting', 'department_targets.unit', 'department_targets.detail', 'target_units.target_1 as target_unit_1', 'target_units.target_2 as target_unit_2', 'target_units.target_3 as target_unit_3', 'target_units.target_4 as target_unit_4', 'target_units.target_5 as target_unit_5', 'target_units.target_6 as target_unit_6', 'target_units.target_7 as target_unit_7', 'target_units.target_8 as target_unit_8', 'target_units.target_9 as target_unit_9', 'target_units.target_10 as target_unit_10', 'target_units.target_11 as target_unit_11', 'target_units.target_12 as target_unit_12')
            ->where('department_targets.id', $id)
            ->where(DB::raw('YEAR(department_targets.date)'), $year)
            ->get();

        return view('actual.input-actual-department-achievement', [
            'title'      => 'Input Data Realisasi',
            'desc'       => 'Department Achievement',
            'department' => $department,
            'targets'    => $targets,
        ]);
    }

    public function storeDept(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $now  = now()->format('d');

        $validator = Validator::make($request->all(), [
            'date'        => 'required',
            'actual'      => 'required',
            'record_file' => 'mimes:jpeg,pdf',
            'achievement' => 'required',

        ]);

        if ($validator->fails()) {
            flash()->error('Please fill all required field and upload a valid file format');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $date = Carbon::createFromDate($request->year, $request->date, 1)->startOfMonth();

        try {
            if ($request->hasFile('record_file')) {
                $recordFile = $request->file('record_file');
                $extension  = $recordFile->getClientOriginalExtension();

                if (in_array($extension, ['jpeg', 'jpg'])) {
                    // Simpan gambar sementara
                    $tempImageName = Str::random(40) . '.' . $extension;
                    $tempImagePath = public_path('temp/' . $tempImageName);

                    // Pastikan folder temp ada
                    if (! file_exists(public_path('temp'))) {
                        mkdir(public_path('temp'), 0755, true);
                    }

                    $recordFile->move(public_path('temp'), $tempImageName);

                    // Buat nama file PDF tujuan
                    $pdfFileName = Str::random(40) . '.pdf';
                    $pdfFilePath = public_path('record_files/' . $pdfFileName);

                    // Buat PDF dari gambar
                    $pdf = app('dompdf.wrapper');
                    $pdf->loadView('pdf.image', ['imagePath' => $tempImagePath]);

                    // Simpan PDF ke folder tujuan
                    if (! file_exists(public_path('record_files'))) {
                        mkdir(public_path('record_files'), 0755, true);
                    }

                    $pdf->save($pdfFilePath);
                    $recordFileName = $pdfFileName;

                    // Hapus gambar sementara
                    unlink($tempImagePath);
                } else {
                    $recordFileName = Str::random(40) . '.' . $recordFile->getClientOriginalExtension();
                    $recordFile->move(public_path('record_files'), $recordFileName);
                }
            }
        } catch (\Exception $e) {
            dd("PDF generation failed: " . $e->getMessage());
        }

        $semester = '';

        if ($request->date > 6 && $request->date <= 12) {
            $semester = '2';
        } else {
            $semester = '1';
        }

        $input_by = Auth::user()->name;

        $actual         = '';
        $target         = '';
        $kpi_percentage = '';
        if ($request->record_file == null) {
            $target         = $request->target;
            $actual         = '0';
            $kpi_percentage = '0';
        } else {
            $cleanedActual  = str_replace(',', '', $request->actual);
            $cleanedTarget  = str_replace(',', '', $request->target);
            $target         = floatval($cleanedTarget);
            $actual         = floatval($cleanedActual);
            $kpi_percentage = $request->achievement;
        }

        $searchConditions = [
            'kpi_code'      => $request->kpi_code,
            'date'          => $date,
            'department_id' => $request->department_id,
        ];
        $actualDeadline         = DB::table('setting_actual_deadlines')->first();
        $existingActual         = DB::table('department_actuals')->where($searchConditions)->first();
        $existingActualApproved = DB::table('department_actuals')->where($searchConditions)
            ->whereIn('status', ['Approved'])->first();
        $existingActualInvalid = DB::table('department_actuals')->where($searchConditions)
            ->whereIn('status', ['Invalid'])->first();
        $revisedActual = DB::table('department_actuals')->where($searchConditions)
            ->whereIn('status', ['Revise'])->first();

        $deadline = $actualDeadline->open_until ?? 15;

        if (! $revisedActual) {
            if ($now > $deadline && ($role == '' || $role == 'Inputer' || $role == 'Checker 1')) {
                flash()->error('Sudah melewati batas pengisian KPI');
                return redirect()->back()->withErrors(['status' => 400]);
            } elseif ($now > $deadline && ($role == 'Checker 2')) {
                flash()->error('Sudah melewati batas pengisian KPI');
                return redirect()->back()->withErrors(['status' => 400]);
            } elseif ($now > $deadline && ($role == 'Mng Approver')) {
                flash()->error('Sudah melewati batas pengisian KPI');
                return redirect()->back()->withErrors(['status' => 400]);
            }
        }

        if (($existingActualApproved || $existingActualInvalid) && $role != 'Approver') {
            flash()->error('Data dinyatakan tidak valid atau sudah melewati Final Check (HRD)');
            return redirect()->back()->withErrors(['status' => 'Cannot update or create record: Data sudah di check atau di approve.']);
        }
        $dataToUpdateOrCreate = [
            'kpi_item'            => $request->kpi_item,
            'kpi_unit'            => $request->kpi_unit,
            'review_period'       => $request->review_period,
            'target'              => $target ?? 0,
            'actual'              => $actual ?? 0,
            'kpi_percentage'      => $kpi_percentage ?? 0,
            'kpi_calculation'     => $request->kpi_calculation,
            'supporting_document' => $request->supporting_document,
            'comment'             => $request->comment,
            'record_file'         => isset($recordFileName) ? $recordFileName : null,
            'department_name'     => $request->department_name,
            'kpi_weighting'       => $request->kpi_weighting,
            'trend'               => $request->trend,
            'status'              => $request->status,
            'semester'            => $semester,
            'detail'              => $request->detail,
            'input_by'            => $input_by,
            'input_at'            => now(),
        ];

        DepartmentActual::updateOrCreate($searchConditions, $dataToUpdateOrCreate);
        flash()->success('Data created successfully.');
        return redirect()->to('actual/input-actual-department-details?department=' . $request->input('department_id') . '&year=' . $request->input('year') . '&semester=' . $semester);
    }

    public function store(Request $request)
    {

        $user = Auth::user();
        $role = $user->role;
        $now  = now()->format('d');
        // dd($now);

        $validator = Validator::make($request->all(), [
            'date'        => 'required',
            'actual'      => 'required',
            'record_file' => 'mimes:jpeg,pdf',
            'achievement' => 'required',
        ]);
        if ($validator->fails()) {
            flash()->error('Please fill all required field and upload a valid file format');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $date = Carbon::createFromDate($request->year, $request->date, 1)->startOfMonth();

        try {
            if ($request->hasFile('record_file')) {
                $recordFile = $request->file('record_file');
                $extension  = $recordFile->getClientOriginalExtension();

                if (in_array($extension, ['jpeg', 'jpg'])) {
                    // Simpan gambar sementara
                    $tempImageName = Str::random(40) . '.' . $extension;
                    $tempImagePath = public_path('temp/' . $tempImageName);

                    // Pastikan folder temp ada
                    if (! file_exists(public_path('temp'))) {
                        mkdir(public_path('temp'), 0755, true);
                    }

                    $recordFile->move(public_path('temp'), $tempImageName);

                    // Buat nama file PDF tujuan
                    $pdfFileName = Str::random(40) . '.pdf';
                    $pdfFilePath = public_path('record_files/' . $pdfFileName);

                    // Buat PDF dari gambar
                    $pdf = app('dompdf.wrapper');
                    $pdf->loadView('pdf.image', ['imagePath' => $tempImagePath]);

                    // Simpan PDF ke folder tujuan
                    if (! file_exists(public_path('record_files'))) {
                        mkdir(public_path('record_files'), 0755, true);
                    }

                    $pdf->save($pdfFilePath);
                    $recordFileName = $pdfFileName;

                    // Hapus gambar sementara
                    unlink($tempImagePath);
                } else {
                    $recordFileName = Str::random(40) . '.' . $recordFile->getClientOriginalExtension();
                    $recordFile->move(public_path('record_files'), $recordFileName);
                }
            }
        } catch (\Exception $e) {
            dd("PDF generation failed: " . $e->getMessage());
        }

        $semester = '';

        if ($request->date > 6 && $request->date <= 12) {
            $semester = '2';
        } else {
            $semester = '1';
        }
        $actual         = '';
        $target         = '';
        $kpi_percentage = '';
        if ($request->record_file == null) {
            $cleanedTarget  = str_replace(',', '', $request->target);
            $target         = floatval($cleanedTarget);
            $actual         = '0';
            $kpi_percentage = '0';
        } else {
            $cleanedActual  = str_replace(',', '', $request->actual);
            $cleanedTarget  = str_replace(',', '', $request->target);
            $target         = floatval($cleanedTarget);
            $actual         = floatval($cleanedActual);
            $kpi_percentage = $request->achievement;
        }

        if ($role == 'Approver' || $role == 'Checker Div 1' || $role == 'Checker Div 2') {
            $input_by = $request->name;
        } else {
            $input_by = Auth::user()->name;
        }

        $searchConditions = [
            'kpi_code'    => $request->kpi_code,
            'date'        => $date,
            'employee_id' => $request->employee_id,
        ];

        $actualDeadline         = DB::table('setting_actual_deadlines')->first();
        $existingActual         = DB::table('actuals')->where($searchConditions)->first();
        $existingActualApproved = DB::table('actuals')->where($searchConditions)
            ->whereIn('status', ['Approved'])->first();
        $existingActualInvalid = DB::table('actuals')->where($searchConditions)
            ->whereIn('status', ['Invalid'])->first();
        $revisedActual = DB::table('actuals')->where($searchConditions)
            ->whereIn('status', ['Revise'])->first();

        $deadline = $actualDeadline->open_until ?? 15;

        if (! $revisedActual) {
            if ($now > $deadline && ($role == '' || $role == 'Inputer' || $role == 'Checker 1')) {
                flash()->error('Sudah melewati batas pengisian KPI');
                return redirect()->back()->withErrors(['status' => 400]);
            } elseif ($now > $deadline && ($role == 'Checker 2')) {
                flash()->error('Sudah melewati batas pengisian KPI');
                return redirect()->back()->withErrors(['status' => 400]);
            } elseif ($now > $deadline && ($role == 'Mng Approver')) {
                flash()->error('Sudah melewati batas pengisian KPI');
                return redirect()->back()->withErrors(['status' => 400]);
            }
        }

        if (($existingActualApproved || $existingActualInvalid) && $role != 'Approver') {
            flash()->error('Data dinyatakan tidak valid atau sudah melewati Final Check (HRD)');
            return redirect()->back()->withErrors(['status' => 'Cannot update or create record: Data sudah di check atau di approve.']);
        }

        $dataToUpdateOrCreate = [
            'kpi_item'            => $request->kpi_item,
            'kpi_unit'            => $request->kpi_unit,
            'review_period'       => $request->review_period,
            'target'              => $target ?? 0,
            'actual'              => $actual ?? 0,
            'kpi_percentage'      => $kpi_percentage ?? 0,
            'kpi_calculation'     => $request->kpi_calculation,
            'supporting_document' => $request->supporting_document,
            'comment'             => $request->comment,
            'record_file'         => isset($recordFileName) ? $recordFileName : null,
            'department_name'     => $request->department_name,
            'kpi_weighting'       => $request->kpi_weighting,
            'trend'               => $request->trend,
            'status'              => $request->status,
            'semester'            => $semester,
            'detail'              => $request->detail,
            'is_valid'            => 1,
            'invalid_weight'      => null,
            'input_by'            => $input_by,
            'input_at'            => now(),
        ];

        Actual::updateOrCreate($searchConditions, $dataToUpdateOrCreate);

        flash()->success('Data created successfully.');
        return redirect()->to('actual/input-actual-employee?employee=' . $request->input('employee_id') . '&year=' . $request->input('year') . '&semester=' . $semester);
    }

    public function updateActual(Request $request)
    {
        $actual = Actual::find($request->actual_id);
        $user   = Auth::user()->name;

        if (! $actual) {
            return view('components/404-page');
        }
        if ($request->filled('status')) {
            $actual->status = $request->status;
            $now            = now()->format('d');
            $newDeadline    = $now + 3;

            if ($request->status == 'Checked 1') {
                $actual->asst_mng_checked_at = now();
                $actual->asst_mng_checked_by = $user;
            } else if ($request->status == 'Checked 2') {
                $actual->checked_at = now();
                $actual->checked_by = $user;
            } elseif ($request->status == 'Mng Approve') {
                $actual->mng_approved_at = now();
                $actual->mng_approved_by = $user;
            } elseif ($request->status == 'Approved') {
                $actual->approved_at = now();
                $actual->approved_by = $user;
            } elseif ($request->status == 'Revise') {
                $actual->deadline = $newDeadline;
                $actual->save();
            }
        }

        $actual->save();
        return response()->json(['message' => 'Status updated successfully']);
    }

    public function updateActualDept(Request $request)
    {
        $actual = DepartmentActual::find($request->actual_id);
        $user   = Auth::user()->name;

        if (! $actual) {
            return view('components/404-page');
        }
        if ($request->filled('status')) {
            $actual->status = $request->status;
            $now            = now()->format('d');
            $newDeadline    = $now + 3;

            if ($request->status == 'Checked 1') {
                $actual->asst_mng_checked_at = now();
                $actual->asst_mng_checked_by = $user;
            } else if ($request->status == 'Checked 2') {
                $actual->checked_at = now();
                $actual->checked_by = $user;
            } elseif ($request->status == 'Mng Approve') {
                $actual->mng_approved_at = now();
                $actual->mng_approved_by = $user;
            } elseif ($request->status == 'Approved') {
                $actual->approved_at = now();
                $actual->approved_by = $user;
                $actual->is_valid = 1;
                $actual->invalid_weight = null;
                $actual->invalid_reason = null;
            } elseif ($request->status == 'Revise') {
                $actual->deadline = $newDeadline;
            }
        }

        $actual->save();
        return response()->json(['message' => 'Status updated successfully']);
    }

    public function batchUpdateActual(Request $request)
    {
        $selectedTargets = explode(',', $request->input('selected_targets', ''));
        $targetCodes     = explode(',', $request->input('target_codes', ''));
        $month           = $request->month;
        $year            = $request->year;
        $user            = Auth::user();
        $name            = $user->name;
        $role            = $user->role;
        $status          = '';
        $from            = $user->email;
        $nik             = $request->nik;
        $userID          = $request->employee_id;

        $sendTo = DB::table('employees')
            ->where('nik', $nik)
            ->select('employees.email')
            ->first();

        $details = [
            'approved_by' => $from,
            'email'       => $sendTo,
            'title'       => 'Notifikasi Persetujuan KPI',
            'msg'         => 'Dengan Hormat saya sampaikan bahwa KPI anda telah disetujui. Terima kasih atas kerjasamanya',
        ];

        // dd($details);
        // dd($targetCodes);

        if ($role == 'Checker 1' || $role == 'Checker Factory' || $role == 'Checker WS') {
            $status = 'Checked 1';
            foreach ($selectedTargets as $index => $targetId) {
                $targetCode = $targetCodes[$index];

                DB::table('actuals')->whereYear('actuals.date', '=', $year)
                    ->whereMonth('actuals.date', '=', $month)
                    ->where('kpi_code', '=', $targetCode)
                    ->where('kpi_code', '!=', '')
                    ->where('status', '=', 'Filled')
                    ->where('employee_id', $userID)
                    ->where('record_file', '!=', '')
                    ->update(
                        [
                            'status'              => $status,
                            'asst_mng_checked_by' => $name,
                            'asst_mng_checked_at' => now(),
                        ]
                    );
            }
        } elseif ($role == 'Checker Div 1' || $role == 'Checker Div 2') {
            $status = 'Checked 2';
            foreach ($selectedTargets as $index => $targetId) {
                $targetCode = $targetCodes[$index];

                DB::table('actuals')->whereYear('actuals.date', '=', $year)
                    ->whereMonth('actuals.date', '=', $month)
                    ->where('kpi_code', '=', $targetCode)
                    ->where('kpi_code', '!=', '')
                    ->where('status', '=', 'Checked 1')
                    ->where('employee_id', $userID)
                    ->where('record_file', '!=', '')
                    ->update(
                        [
                            'status'     => $status,
                            'checked_by' => $name,
                            'checked_at' => now(),
                        ]
                    );
            }
        } elseif ($role == 'Mng Approver') {
            $status = 'Mng Approve';
            foreach ($selectedTargets as $index => $targetId) {
                $targetCode = $targetCodes[$index];

                DB::table('actuals')->whereYear('actuals.date', '=', $year)
                    ->whereMonth('actuals.date', '=', $month)
                    ->where('kpi_code', '=', $targetCode)
                    ->where('kpi_code', '!=', '')
                    ->where('employee_id', $userID)
                    ->where('record_file', '!=', '')
                    ->update(
                        [
                            'status'          => $status,
                            'mng_approved_by' => $name,
                            'mng_approved_at' => now(),
                        ]
                    );
            }
        } else if ($role == 'Approver') {
            $status = 'Approved';
            foreach ($selectedTargets as $index => $targetId) {
                $targetCode = $targetCodes[$index];

                DB::table('actuals')->whereYear('actuals.date', '=', $year)
                    ->whereMonth('actuals.date', '=', $month)
                    ->where('kpi_code', '=', $targetCode)
                    ->where('kpi_code', '!=', '')
                    ->where('employee_id', $userID)
                    ->where('record_file', '!=', '')
                    ->update(
                        [
                            'status'      => $status,
                            'approved_by' => $name,
                            'approved_at' => now(),
                        ]
                    );
            }
        } else {
            return back()->with('error', 'An Error Occured');
        }

        if ($sendTo != null || $sendTo != 0) {
            ApproveEmail::dispatch($details);
        }

        return redirect()->back()->with('success', 'Data Updated Successfully');
    }

    public function batchUpdateActualDept(Request $request)
    {
        $selectedTargets = explode(',', $request->input('selected_targets', ''));
        $targetCodes     = explode(',', $request->input('target_codes', ''));
        $month           = $request->month;
        $year            = $request->year;
        $user            = Auth::user();
        $from            = $user->email;
        $name            = $user->name;
        $role            = $user->role;
        $status          = '';
        $departmentID    = $request->department_id;

        $sendTo = DB::table('employees')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->where('employees.department_id', $departmentID)
            ->where('employees.occupation', '=', 'Asst Mng')
            ->select('employees.email')
            ->first();

        $details = [
            'approved_by' => $from,
            'email'       => $sendTo,
            'title'       => 'Notifikasi Persetujuan KPI',
            'msg'         => 'Dengan Hormat saya sampaikan bahwa KPI Departemen anda telah disetujui. Terima kasih atas kerjasamanya',
        ];

        // dd($details);

        if ($role == 'Checker 1' || $role == 'Checker Factory' || $role == 'Checker WS') {
            $status = 'Checked 1';
            foreach ($selectedTargets as $index => $targetId) {
                $targetCode = $targetCodes[$index];

                DB::table('department_actuals')->whereYear('department_actuals.date', '=', $year)
                    ->whereMonth('department_actuals.date', '=', $month)
                    ->where('kpi_code', '=', $targetCode)
                    ->where('kpi_code', '!=', '')
                    ->where('status', '=', 'Filled')
                    ->where('record_file', '!=', '')
                    ->update(
                        [
                            'status'              => $status,
                            'asst_mng_checked_by' => $name,
                            'asst_mng_checked_at' => now(),
                        ]
                    );
            }
        } elseif ($role == 'Checker Div 1' || $role == 'Checker Div 2') {
            $status = 'Checked 2';
            foreach ($selectedTargets as $index => $targetId) {
                $targetCode = $targetCodes[$index];

                DB::table('department_actuals')->whereYear('department_actuals.date', '=', $year)
                    ->whereMonth('department_actuals.date', '=', $month)
                    ->where('kpi_code', '=', $targetCode)
                    ->where('kpi_code', '!=', '')
                    ->where('status', '=', 'Checked 1')
                    ->where('record_file', '!=', '')
                    ->update(
                        [
                            'status'     => $status,
                            'checked_by' => $name,
                            'checked_at' => now(),
                        ]
                    );
            }
        } elseif ($role == 'Mng Approver') {
            $status = 'Mng Approve';
            foreach ($selectedTargets as $index => $targetId) {
                $targetCode = $targetCodes[$index];

                DB::table('department_actuals')->whereYear('department_actuals.date', '=', $year)
                    ->whereMonth('department_actuals.date', '=', $month)
                    ->where('kpi_code', '=', $targetCode)
                    ->where('kpi_code', '!=', '')
                    ->where('record_file', '!=', '')
                    ->update(
                        [
                            'status'          => $status,
                            'mng_approved_by' => $name,
                            'mng_approved_at' => now(),
                        ]
                    );
            }
        } else if ($role == 'Approver') {
            $status = 'Approved';
            foreach ($selectedTargets as $index => $targetId) {
                $targetCode = $targetCodes[$index];

                DB::table('department_actuals')->whereYear('department_actuals.date', '=', $year)
                    ->whereMonth('department_actuals.date', '=', $month)
                    ->where('kpi_code', '=', $targetCode)
                    ->where('kpi_code', '!=', '')
                    ->where('record_file', '!=', '')
                    ->update(
                        [
                            'status'      => $status,
                            'approved_by' => $name,
                            'approved_at' => now(),
                        ]
                    );
            }
        } else {
            return back()->with('error', 'An Error Occured');
        }

        if ($sendTo != null || $sendTo != 0) {
            ApproveEmail::dispatch($details);
        }

        return redirect()->back()->with('success', 'Data Updated Successfully');
    }

    public function editDeadline(Request $request)
    {
        $year = $request->query('year');

        return view('actual.setting-actual-deadline', [
            'title' => 'Atur Deadline Input',
            'desc'  => 'KPI',
            'year'  => $year,
        ]);
    }

    public function updateDeadline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deadline' => 'required',
            'month'    => 'required',
            'year'     => 'required',
        ]);

        if ($validator->fails()) {
            flash()->error('Please fill all required field');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $deadline = $request->deadline;
        $month    = $request->month;
        $year     = $request->year;

        DB::table('actuals')->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->update(['deadline' => $deadline]);

        DB::table('department_actuals')->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->update(['deadline' => $deadline]);
        DB::table('setting_actual_deadlines')->update(['open_until' => $deadline]);

        flash()->success('Deadline berhasil diperbarui.');
        return redirect()->back();
    }

    public function viewInputReminder()
    {
        $employees = DB::table('employees')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('email', '!=', null)
            ->where('email', '!=', '')
            ->where('email', '!=', "0")
            ->select('employees.*', 'departments.name as department_name', 'departments.id as department_id')
            ->paginate(10);
        // dd($employees);
        return view('actual.send-input-actual-reminder', ['title' => 'Send Input Reminder', 'desc' => 'Notification', 'employees' => $employees]);
    }

    public function sendReminderInput()
    {
        $details = [
            'title'     => 'Notifikasi Pengingat Pengisian Data KPI',
            'greetings' => 'Yth. ',
            'name'      => '',
            'msg'       => 'Mengingatkan kembali untuk mengisi data KPI dan mengupload data pendukung KPI yang sesuai.',
            'msg2'      => 'Jika anda sudah mengisi data KPI dan data pendukung KPI, abaikan email ini.',
            'closing'   => 'Terima kasih atas perhatian dan kerjasamanya.',
            'email'     => '',
        ];

        $sendTo = DB::table('employees')
            ->where('employees.role', '!=', 'Mng Approver')
            ->select('employees.email', 'employees.name')
            ->get();

        foreach ($sendTo as $email) {
            $details['email'] = $email->email;
            $details['name']  = $email->name;

            if ($details['email'] !== null && $details['email'] !== '' && $details['email'] !== 0) {
                ReminderInputEmail::dispatch($details);
            }
            Log::channel('laravel-worker')->info(
                'ReminderInputEmail sent to: ' . $details['email']
            );
        }

        return redirect()->back()->with('success', 'Reminder Input Email Sent Successfully');
    }

    public function sendReminderCheck1()
    {
        $details = [
            'title'     => 'Notifikasi Pengingat Pengecekan (Check 1) Data KPI',
            'greetings' => 'Yth. ',
            'name'      => '',
            'msg'       => 'Mengingatkan kembali untuk melakukan pengecekan (Check 1) pada data KPI dan data pendukung KPI yang sudah diinputkan.',
            'msg2'      => 'Jika anda sudah melakukan pengecekan data KPI dan data pendukung KPI, abaikan email ini.',
            'closing'   => 'Terima kasih atas perhatian dan kerjasamanya.',
            'email'     => '',
        ];

        $sendTo = DB::table('employees')
            ->where('employees.occupation', '=', 'Asst Mng')
            ->select('employees.email', 'employees.name')
            ->get();

        foreach ($sendTo as $email) {
            $details['email'] = $email->email;
            $details['name']  = $email->name;

            if ($details['email'] !== null && $details['email'] !== '' && $details['email'] !== 0) {
                ReminderCheck1Email::dispatch($details);
            }
        }
        Log::channel('laravel-worker')->info(
            'ReminderCheck1Email sent to: ' . $details['email']
        );

        return redirect()->back()->with('success', 'Reminder Check 1 Email Sent Successfully');
    }

    public function sendReminderCheck2()
    {
        $details = [
            'title'     => 'Notifikasi Pengingat Pengecekan (Check 2) Data KPI',
            'greetings' => 'Yth. ',
            'name'      => '',
            'msg'       => 'Mengingatkan kembali untuk melakukan pengecekan (Check 2) pada data KPI dan data pendukung KPI yang sudah diinputkan.',
            'msg2'      => 'Jika anda sudah melakukan pengecekan data KPI dan data pendukung KPI, abaikan email ini.',
            'closing'   => 'Terima kasih atas perhatian dan kerjasamanya.',
            'email'     => '',
        ];

        $sendTo = DB::table('employees')
            ->whereIn('employees.role', ['Checker Div 1', 'Checker Div 2'])
            ->select('employees.email', 'employees.name')
            ->get();

        foreach ($sendTo as $email) {
            $details['email'] = $email->email;
            $details['name']  = $email->name;

            if ($details['email'] !== null && $details['email'] !== '' && $details['email'] !== 0) {
                ReminderCheck2Email::dispatch($details);
            }
        }
        Log::channel('laravel-worker')->info(
            'ReminderCheck2Email sent to: ' . $details['email']
        );

        return redirect()->back()->with('success', 'Reminder Check 2 Email Sent Successfully');
    }

    public function sendReminderMngApproval()
    {
        $details = [
            'title'     => 'Notifikasi Pengingat Persetujuan Data KPI',
            'greetings' => 'Yth. ',
            'name'      => '',
            'msg'       => 'Mengingatkan kembali untuk melakukan persetujuan (Approve) pada data KPI dan data pendukung KPI yang sudah diinputkan.',
            'msg2'      => 'Jika anda sudah melakukan persetujuan data KPI dan data pendukung KPI, abaikan email ini.',
            'closing'   => 'Terima kasih atas perhatian dan kerjasamanya.',
            'email'     => '',
        ];

        $sendTo = DB::table('employees')
            ->where('employees.role', '=', 'Mng Approver')
            ->select('employees.email', 'employees.name')
            ->get();

        foreach ($sendTo as $email) {
            $details['email'] = $email->email;
            $details['name']  = $email->name;

            if ($details['email'] !== null && $details['email'] !== '' && $details['email'] !== 0) {
                ReminderApproveEmail::dispatch($details);
            }
        }
        Log::channel('laravel-worker')->info(
            'ReminderApproveEmail sent to: ' . $details['email']
        );

        return redirect()->back()->with('success', 'Reminder Mng Approval Email Sent Successfully');
    }
}
