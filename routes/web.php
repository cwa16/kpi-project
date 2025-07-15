<?php

use App\Models\Actual;
use App\Models\Preview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ActualController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ActionPlanController;
use App\Http\Controllers\GeneratePdfController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SupportingDocumentController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

// Auth Routes
Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('auth/me', [AuthController::class, 'authMe'])->name('auth.me');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');


Route::get('/reset-password', function () {
    return view('reset-password');
})->name('reset-password');

Route::post('/reset-password/reset', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink(
        $request->only('email')
    );
    if ($status !== Password::RESET_LINK_SENT) {
        return back()->withErrors(['email' => __($status)])->with('error', __($status));
    } else {
        return redirect()->to('/')->with('success', __($status));
    }
})->name('password.reset');

Route::get('/reset-password/{token}', function (string $token) {
    return view('reset-password-token', ['token' => $token]);
})->name('reset-password-token');

Route::post('/reset-password/update', [UserController::class, 'updatePassword'])->name('password.update');




Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [EmployeeController::class, 'index'])->name('dashboard');
    Route::get('dashboard/filter', [EmployeeController::class, 'filter']);

    Route::prefix('users')->group(function () {
        Route::get('/list', [UserController::class, 'index'])->name('user.index');
        Route::get('/users/search', [UserController::class, 'search'])->name('user.search');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::put('/delete/{id}', [UserController::class, 'softDelete'])->name('user.softDelete');
    });

    Route::prefix('target')->group(function () {
        Route::get('/input-target-kpi', [TargetController::class, 'show'])->name('target.show');
        Route::get('/input-target-employee/edit/{id}', [TargetController::class, 'edit'])->name('target.edit');
        Route::get('/input-target-department', [TargetController::class, 'department'])->name('target.department');
        Route::get('/input-target-kpi-department/edit/{id}', [TargetController::class, 'editDept'])->name('target.editDept');
        Route::get('/input-target-kpi-department', [TargetController::class, 'showDept'])->name('target.showDept');

        Route::get('/input-target-department-all', [TargetController::class, 'deptList'])->name('target.departmentAll');
        Route::get('/input-target-department-one', [TargetController::class, 'showDeptOne'])->name('target.showDeptOne');

        Route::get('/import-target-kpi-employee', [TargetController::class, 'showImport'])->name('target.showImport');
        Route::get('/import-target-kpi-department', [TargetController::class, 'showImportDept'])->name('target.showImportDept');
        Route::get('/setting-target-deadline', [TargetController::class, 'settingTargetDeadline'])->name('target.settingTargetDeadline');
        Route::get('/create-target', [TargetController::class, 'createTarget'])->name('target.createTarget');
        Route::get('/create-target-dept', [TargetController::class, 'createTargetDept'])->name('target.createTargetDept');


        Route::post('/input-target-kpi/store', [TargetController::class, 'storeTargetKpi'])->name('target.storeTargetKpi');
        Route::post('/input-target-kpi-department/store', [TargetController::class, 'storeTargetKpiDept'])->name('target.storeTargetKpiDept');
        Route::post('/import-target-kpi-employee/store', [TargetController::class, 'import'])->name('target.import');
        Route::post('/import-target-kpi-department/store', [TargetController::class, 'importDept'])->name('target.importDept');
        Route::put('/input-target-kpi/update', [TargetController::class, 'update'])->name('target.update');
        Route::put('/input-target-kpi-department/update', [TargetController::class, 'updateDept'])->name('target.updateDept');
        Route::put('/input-target-kpi/updateDeadline', [TargetController::class, 'updateTargetDeadline'])->name('target.updateTargetDeadline');

        // Route::get('/input-target-kpi/{id}', [TargetController::class, 'show']);
    });

    Route::prefix('actual')->group(function () {
        Route::get('/input-actual-employee', [ActualController::class, 'show'])->name('actual.show');
        Route::get('/input-actual-department', [ActualController::class, 'department'])->name('actual.department');
        Route::get('/input-actual-achievement/edit/{id}', [ActualController::class, 'edit'])->name('actual.edit');
        Route::post('/input-actual-achievement/store', [ActualController::class, 'store'])->name('actual.store');
        Route::put('/input-actual-achievement/update', [ActualController::class, 'updateActual'])->name('actual.updateActual');
        Route::put('/input-actual-achievement/updateDept', [ActualController::class, 'updateActualDept'])->name('actual.updateActualDept');
        // Preview
        Route::post('/preview/store', [PreviewController::class, 'store'])->name('preview.store');
        Route::get('/preview/{id}/{kpi_code}', [PreviewController::class, 'show'])->name('preview.show');
        Route::get('/preview/{id}/{kpi_code}/filter', [PreviewController::class, 'filter']);
        // Preview End
        // Actual Dept
        Route::get('/input-actual-department-details', [ActualController::class, 'showDept'])->name('actual.showDept');
        Route::get('/input-actual-department-achievement/edit/{id}', [ActualController::class, 'editDept'])->name('actual.editDept');
        Route::post('/input-actual-department-achievement/store-dept', [ActualController::class, 'storeDept'])->name('actual.storeDept');
        Route::put('/input-actual-department-achievement/batchUpdate', [ActualController::class, 'batchUpdateActual'])->name('actual.batchUpdateActual');
        Route::put('/input-actual-department-achievement/batchUpdateDept', [ActualController::class, 'batchUpdateActualDept'])->name('actual.batchUpdateActualDept');
        Route::get('/setting-actual-deadline', [ActualController::class, 'editDeadline'])->name('actual.editDeadline');
        Route::put('/setting-actual-deadline/update', [ActualController::class, 'updateDeadline'])->name('actual.updateDeadline');
        Route::get('/send-input-reminder', [ActualController::class, 'viewInputReminder'])->name('actual.viewInputReminder');
        Route::post('/send-input-reminder/input', [ActualController::class, 'sendReminderInput'])->name('actual.sendReminderInput');
        Route::post('/send-input-reminder/check1', [ActualController::class, 'sendReminderCheck1'])->name('actual.sendReminderCheck1');
        Route::post('/send-input-reminder/check2', [ActualController::class, 'sendReminderCheck2'])->name('actual.sendReminderCheck2');
        Route::post('/send-input-reminder/approve', [ActualController::class, 'sendReminderMngApproval'])->name('actual.sendReminderMngApproval');
    });

    Route::prefix('report')->group(function () {
        Route::get('/list-employee-report', [ReportController::class, 'index'])->name('report.index');
        Route::get('list-department-report', [ReportController::class, 'indexDept'])->name('report.indexDept');
        Route::get('/list-kpi-department-report', [ReportController::class, 'indexDeptTargetReport'])->name('report.indexDeptTargetReport');
        Route::get('/employee-report/{id}', [ReportController::class, 'show'])->name('report.show');
        Route::get('/summary-department-report', [ReportController::class, 'summaryDept'])->name('report.summaryDept');
        Route::get('/department-report/{id}', [ReportController::class, 'department'])->name('report.department');
        Route::get('/file-preview', [ReportController::class, 'showFile'])->name('report.showFile');
        Route::get('/file-preview-dept', [ReportController::class, 'showFileDept'])->name('report.showFileDept');
        Route::get('/kpi-department-report', [ReportController::class, 'departmentTargetReport'])->name('report.departmentTargetReport');
        Route::post('/set-invalid', [ReportController::class, 'setDataInvalid'])->name('report.setInvalid');
        Route::post('/set-invalid-dept', [ReportController::class, 'setDataInvalidDept'])->name('report.setInvalidDept');
    });

    Route::prefix('logs')->group(function () {
        Route::get('/log-check', [LogController::class, 'index'])->name('log-check.index');
        Route::get('/log-input', [LogController::class, 'indexInput'])->name('log-input.indexInput');
        Route::get('/log-input-individual', [LogController::class, 'individual'])->name('log-input.individual');
        Route::get('/log-input-monitoring-employee', [LogController::class, 'indexMonitoringEmployee'])->name('log-input.monitoringEmployee');
        Route::get('/log-input-monitoring-dept', [LogController::class, 'indexMonitoringDept'])->name('log-input.monitoringDept');
        Route::get('/jobs-log', [LogController::class, 'jobsLog'])->name('log.jobsLog');
    });

    Route::prefix('action-plan')->group(function () {
        Route::get('/action-plans', [ActionPlanController::class, 'show'])->name('action-plan.show');
        Route::get('/input-action-plan/{id}', [ActionPlanController::class, 'addEmployeeFile'])->name('action-plan.addEmployeeFile');
        Route::get('/input-action-plan-dept/{id}', [ActionPlanController::class, 'addDeptFile'])->name('dept-action-plan.addDeptFile');
        Route::post('/input-action-plan/store', [ActionPlanController::class, 'storeFile'])->name('action-plan.storeFile');
        Route::post('/input-action-plan/store-dept', [ActionPlanController::class, 'storeFileDept'])->name('dept-action-plan.storeFileDept');
        Route::get('/file-preview/{id}', [ActionPlanController::class, 'showFile'])->name('action-plan.showFile');
        Route::get('/input-action-plan/edit/{id}', [ActionPlanController::class, 'editFile'])->name('action-plan.editFile');
        Route::get('/input-action-plan/edit-dept/{id}', [ActionPlanController::class, 'editDeptFile'])->name('dept-action-plan.editDeptFile');
        Route::put('/input-action-plan/update/{id}', [ActionPlanController::class, 'updateFile'])->name('action-plan.updateFile');
        Route::put('/input-action-plan/update-dept', [ActionPlanController::class, 'updateDeptFile'])->name('dept-action-plan.updateDeptFile');
    });
    Route::prefix('kpi-requirement')->group(function () {
        Route::get('/view-requirement', [RequirementController::class, 'index'])->name('requirement.index');
        Route::get('/create-requirement', [RequirementController::class, 'create'])->name('requirement.create');
        Route::post('/create-requirement/store', [RequirementController::class, 'store'])->name('requirement.store');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/all', [NotificationController::class, 'index'])->name('notification.index');
        Route::get('/create', [NotificationController::class, 'create'])->name('notification.create');
        Route::post('/store', [NotificationController::class, 'store'])->name('notification.store');
    });

    Route::get('generate-pdf-input', [GeneratePdfController::class, 'generatePdfInput'])->name('generatePdfInput');
    Route::get('generate-pdf-check', [GeneratePdfController::class, 'generatePdfCheck'])->name('generatePdfCheck');

    Route::post('/email/send', [MailController::class, 'sendEmail'])->name('email.sendEmail');
    Route::post('/email-dept/send', [MailController::class, 'sendEmailDept'])->name('email.sendEmailDept');

    Route::get('/master-input', [EmployeeController::class, 'indexMasterInput'])->name('masterInput');
    Route::post('/master-input/update', [EmployeeController::class, 'updateMasterInput'])->name('updateMasterInput');

    Route::get('/supporting-document-employee', [SupportingDocumentController::class, 'index'])->name('supportingDocumentEmployee');
    Route::get('/supporting-document-dept', [SupportingDocumentController::class, 'indexDept'])->name('supportingDocumentDept');
    Route::get('/supporting-document-employee-list', [SupportingDocumentController::class, 'employeeSupportingDocumentList'])->name('employeeSupportingDocumentList');
    Route::get('/supporting-document-dept-list', [SupportingDocumentController::class, 'departmentSupportingDocumentList'])->name('deptSupportingDocumentList');
    Route::get('/supporting-document-file', [SupportingDocumentController::class, 'showFile'])->name('supportingDocumentFile');
    Route::get('/supporting-document-file-dept', [SupportingDocumentController::class, 'showFileDept'])->name('supportingDocumentFileDept');
});



Route::get('404-not-found', function () {
    return view('components/404-page');
})->name('404-page');
