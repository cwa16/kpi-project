<div class="fixed left-0 top-0 w-64 h-full bg-[#0F1035] p-4 overflow-y-auto">
    <a href="#" class="flex items-center pb-4 border-b border-b-gray-300">
        <img src="https://via.placeholder.com/150" alt="logo" class="w-12 h-12 rounded object-cover">
        <span class="text-base font-bold text-gray-200 ml-3">Key Performance Indicator</span>
    </a>

    @php
        $user = auth()->user();
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $semester = $currentMonth < 7 ? '1' : '2';
        $userID = $user->id;
        $role = $user->role;
        $userNik = $user->nik;
        $departmentID = $user->department_id;
        $inputType = $user->input_type;

        $canApproveFinal = \App\Models\ApprovalMatrix::where('employee_nik', $userNik)
            ->where('approval_type', 'Approver')
            ->exists();
    @endphp

    <ul class="mt-4 space-y-1 text-sm">
        <li>
            <a href="{{ route('dashboard') }}?department=&name=&year=&semester="
                class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700">
                <i class="ri-dashboard-2-line text-xl"></i><span class="ml-3">Dashboard</span>
            </a>
        </li>

        @if ($inputType == 'Group')
            <li><a href="{{ route('target.department', 'department=' . $departmentID) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-crosshair-2-line text-xl"></i><span class="ml-3">Input Target</span></a></li>
            <li><a href="{{ route('actual.department', 'department=' . $departmentID) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-book-2-line text-xl"></i><span class="ml-3">Input Pencapaian Aktual</span></a></li>
        @elseif ($inputType == 'Individual')
            <li><a href="{{ route('target.department', 'employee=' . $userID) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-crosshair-2-line text-xl"></i><span class="ml-3">Input Target</span></a></li>
            <li><a href="{{ route('actual.department', 'employee=' . $userID) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-book-2-line text-xl"></i><span class="ml-3">Input Pencapaian Aktual</span></a></li>
        @endif

        <li class="border-b border-gray-600 my-2"></li>

        @if ($inputType == 'Group')
            <li><a href="{{ route('report.index', 'department=' . $departmentID) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-contacts-book-2-line text-xl"></i><span class="ml-3">KPI Report
                        (Employees)</span></a></li>
        @elseif ($inputType == 'Individual')
            <li><a href="{{ route('report.index', 'employee=' . $userID) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-contacts-book-2-line text-xl"></i><span class="ml-3">KPI Report
                        (Employees)</span></a></li>
        @endif

        @if ($role == '' && $role == 'Inputer')
            <li><a href="{{ route('report.department', $departmentID . '?semester=&year=' . $currentYear) }}"
                    id="department-link" class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-file-list-3-fill text-xl"></i><span class="ml-3">KPI Report (Dept)</span></a></li>
        @else
            <li><a href="{{ route('report.indexDept') }}" id="department-link"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-file-list-3-fill text-xl"></i><span class="ml-3">KPI Report (Dept)</span></a></li>
        @endif

        @if ($role == 'Approver' || $role == 'Mng Approver')
            <li class="border-b border-gray-600 my-2"></li>
            <li><a href="{{ route('report-year.summaryDept', 'year=' . $currentYear) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-line-chart-line text-xl"></i><span class="ml-3">Summary KPI Report (Employees) Full
                        Year</span></a></li>
            <li><a href="{{ route('report-year.departmentTargetReport', 'year=' . $currentYear) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-bar-chart-box-line text-xl"></i><span class="ml-3">Summary KPI Report
                        (Dept)</span></a></li>
        @endif

        @if ($canApproveFinal)
            <li class="border-b border-gray-600 my-2"></li>
            <li><a href="{{ route('report-year.departmentTargetReport', 'year=' . $currentYear) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-bar-chart-box-line text-xl"></i><span class="ml-3">Summary KPI Report
                        (Dept)</span></a></li>
        @endif

        <li class="border-b border-gray-600 my-2"></li>

        <li><a href="{{ route('masterSupportingDocument') }}?department={{ $departmentID }}"
                class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                    class="ri-database-line text-xl"></i><span class="ml-3">Contoh Form Data Pendukung</span></a></li>
        <li><a href="{{ route('supportingDocumentEmployee') }}?department={{ $departmentID }}"
                class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                    class="ri-database-line text-xl"></i><span class="ml-3">Lihat Data Pendukung</span></a></li>

        <li class="border-b border-gray-600 my-2"></li>

        @if ($role == 'Approver' || $role == 'Mng Approver')
            <li><a href="{{ route('log-check.index', 'year=' . $currentYear . '&semester=' . $semester) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-file-check-line text-xl"></i><span class="ml-3">Log Pengecekan</span></a></li>
        @endif

        @if ($role != '' && $role != 'Checker 1' && $role != 'Checker Factory' && $role != 'Checker WS')
            @if ($role == 'Checker Div 1' || $role == 'Checker Div 2')
                <li><a href="{{ route('log-input.indexInput', 'department=' . $departmentID . '&month=' . $currentMonth . '&year=' . $currentYear) }}"
                        class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                            class="ri-history-line text-xl"></i><span class="ml-3">Log Check</span></a></li>
            @else
                <li><a href="{{ route('log-input.indexInput', 'department=' . $departmentID . '&month=' . $currentMonth . '&year=' . $currentYear) }}"
                        class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                            class="ri-history-line text-xl"></i><span class="ml-3">Log Input</span></a></li>
            @endif
        @endif

        @if ($role == 'Approver' || $role == 'Mng Approver')
            <li><a href="{{ route('log-input.individual', 'department=' . $departmentID . '&month=' . $currentMonth . '&year=' . $currentYear) }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-user-follow-fill text-xl"></i><span class="ml-3">Log Input Individual</span></a>
            </li>
            <li><a href="{{ route('log-input.monitoringEmployee') }}?department=all&semester={{ $semester }}&year={{ $currentYear }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-pass-valid-line text-xl"></i><span class="ml-3">Log Input Aktual Employee</span></a>
            </li>
            <li><a href="{{ route('log-input.monitoringDept') }}?semester={{ $semester }}&year={{ $currentYear }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-building-line text-xl"></i><span class="ml-3">Log Input Aktual Dept</span></a></li>
            <li><a href="{{ route('log.jobsLog') }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-file-history-line text-xl"></i><span class="ml-3">Queue Log</span></a></li>
        @endif

        @if ($role == 'Approver')
            <li class="border-b border-gray-600 my-2"></li>
            <li><a href="{{ route('masterInput') }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-layout-left-fill text-xl"></i><span class="ml-3">Master Input</span></a></li>
            <li><a href="{{ route('user.index') }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-group-2-fill text-xl"></i><span class="ml-3">Master Employees</span></a></li>
            <li><a href="{{ route('approval_matrix.index') }}"
                    class="flex items-center py-1.5 px-6 text-gray-300 hover:bg-gray-700"><i
                        class="ri-group-2-fill text-xl"></i><span class="ml-3">Approval Matrix</span></a></li>
        @endif
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const departmentLink = document.getElementById('department-link');
        if (departmentLink) {
            const url = new URL(departmentLink.href);
            url.searchParams.set('semester', localStorage.getItem('selectedSemester') || '');
            url.searchParams.set('year', localStorage.getItem('selectedYear') || '');
            departmentLink.href = url.toString();
        }
    });
</script>
