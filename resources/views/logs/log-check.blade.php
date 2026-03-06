<x-app-layout :title="$title" :desc="$desc">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <style>
        /* Custom styling agar DataTables mengikuti gaya Tailwind Anda */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            @apply border border-gray-300 rounded px-2 py-1 text-sm focus:ring-blue-500 outline-none mb-4;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            @apply text-xs text-gray-500 mt-4;
        }

        /* Menyesuaikan pagination button */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-blue-600 text-white border-blue-600 rounded;
        }
    </style>
    <div class="ml-60 mt-4 overflow-x-auto p-2 bg-gray-100 border border-gray-200 shadow-md shadow-black/10 rounded-md">
        @php
            $i = 0;
            $currentYear = Carbon\Carbon::now()->year;
            $startYear = 2024;
            $endYear = $currentYear + 2;
            $semesterQuery = request()->query('semester');

        @endphp
        <div class="flex justify-between">
            <div class="p-2">
                <div class="px-1">
                    <span class="font-medium text-gray-600 text-sm">PT BRIDGESTONE KALIMANTAN PLANTATION</span>
                </div>
                <div class="px-1">
                    <span class=" font-bold text-gray-600 text-2xl">LOG pengecekan dan verifikasi data pendukung KPI</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <form action="" method="GET" class="d-flex gap-2">
                    <select name="month" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Pilih Bulan --</option>
                        @foreach ($months as $m)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="form-control" onchange="this.form.submit()">
                        <option value="2024" {{ $selectedYear == 2024 ? 'selected' : '' }}>2024</option>
                        <option value="2025" {{ $selectedYear == 2025 ? 'selected' : '' }}>2025</option>
                    </select>
                </form>
            </div>

            <div class="card-body">

                {{-- TABEL 1: KPI DEPARTMENT --}}
                <h5>KPI Dept.</h5>
                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table id="tableDept" class="w-full text-left text-xs sm:text-sm align-middle">
                        <thead class="bg-slate-800 text-white uppercase tracking-wider">
                            <tr>
                                <th rowspan="2" class="px-3 py-3 text-center border-r border-slate-700 bg-blue-700">
                                    No</th>
                                <th rowspan="2" class="px-4 py-3 border-r border-slate-700 bg-blue-700">Dept</th>
                                <th rowspan="2" class="px-3 py-3 text-center border-r border-slate-700 bg-blue-700">
                                    Jlh KPI</th>
                                <th colspan="2"
                                    class="px-4 py-2 text-center border-b border-r border-slate-700 bg-blue-700">Cek
                                    1 (Asst Mng)</th>
                                <th colspan="2"
                                    class="px-4 py-2 text-center border-b border-r border-slate-700 bg-blue-700">Cek
                                    2 (Checked)</th>
                                <th colspan="2"
                                    class="px-4 py-2 text-center border-b border-r border-slate-700 bg-blue-700">
                                    Approved (Mgr)</th>
                                <th colspan="2" class="px-4 py-2 text-center border-b border-slate-700 bg-blue-700">
                                    Verifikasi
                                </th>
                            </tr>
                            <tr class="bg-slate-700/50 text-[10px]">
                                <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Oleh</th>
                                <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Result</th>
                                <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Oleh</th>
                                <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Result</th>
                                <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Oleh</th>
                                <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Result</th>
                                <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Oleh</th>
                                <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Result</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white text-gray-700">
                            @foreach ($deptLogs as $key => $d)
                                <tr class="even:bg-slate-50 hover:bg-blue-50 transition-colors">
                                    <td class="px-3 py-2 text-center border-r border-gray-100 font-medium">
                                        {{ $key + 1 }}</td>
                                    <td class="px-4 py-2 border-r border-gray-100 font-semibold text-slate-900">
                                        {{ $d->department_code }}</td>
                                    <td class="px-3 py-2 text-center border-r border-gray-100 italic">
                                        {{ $d->total_kpi }}</td>

                                    {{-- Cek 1 --}}
                                    <td class="px-3 py-2 border-r border-gray-50 text-gray-500">
                                        {{ $d->cek1_by ?: '-' }}</td>
                                    <td class="px-3 py-2 border-r border-gray-100">
                                        @if ($d->cek1_count > 0)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                {{ $d->cek1_count }} OK
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Cek 2 --}}
                                    <td class="px-3 py-2 border-r border-gray-50 text-gray-500">
                                        {{ $d->cek2_by ?: '-' }}</td>
                                    <td class="px-3 py-2 border-r border-gray-100">
                                        @if ($d->cek2_count > 0)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                {{ $d->cek2_count }} OK
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Approved --}}
                                    <td class="px-3 py-2 border-r border-gray-50 text-gray-500">{{ $d->app_by ?: '-' }}
                                    </td>
                                    <td class="px-3 py-2 border-r border-gray-100">
                                        @if ($d->app_count > 0)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                {{ $d->app_count }} OK
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Verifikasi --}}
                                    <td class="px-3 py-2 border-r border-gray-50 text-gray-500 text-center">
                                        {{ $d->ver_by ?: '-' }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if ($d->ver_count > 0)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                {{ $d->ver_count }} OK
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-bold text-slate-700 flex items-center">
                            <span class="w-2 h-6 bg-blue-600 rounded-full mr-2"></span>
                            KPI Employees
                        </h5>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                        <table id="tableEmp" class="w-full text-left text-xs align-middle leading-tight">
                            <thead class="bg-slate-800 text-white uppercase tracking-wider">
                                <tr>
                                    <th rowspan="2"
                                        class="px-3 py-4 text-center border-r border-slate-700 bg-blue-700">No</th>
                                    <th rowspan="2" class="px-3 py-4 border-r border-slate-700 bg-blue-700">Dept</th>
                                    <th rowspan="2" class="px-4 py-4 border-r border-slate-700 bg-blue-700">Nama</th>
                                    <th rowspan="2" class="px-3 py-4 border-r border-slate-700 bg-blue-700">Posisi
                                    </th>
                                    <th rowspan="2"
                                        class="px-2 py-4 text-center border-r border-slate-700 bg-blue-700">Jlh</th>
                                    <th colspan="2"
                                        class="px-4 py-2 text-center border-b border-r border-slate-700 bg-blue-700">Cek
                                        1 (Asst)
                                    </th>
                                    <th colspan="2"
                                        class="px-4 py-2 text-center border-b border-r border-slate-700 bg-blue-700">Cek
                                        2
                                        (Checked)</th>
                                    <th colspan="2"
                                        class="px-4 py-2 text-center border-b border-r border-slate-700 bg-blue-700">App
                                        (Mgr)</th>
                                    <th colspan="2"
                                        class="px-4 py-2 text-center border-b border-slate-700 bg-blue-700">
                                        Verifikasi</th>
                                </tr>
                                <tr class="bg-slate-700/50 text-[10px]">
                                    <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Oleh</th>
                                    <th class="px-3 py-2 border-r border-slate-700 text-center bg-blue-700">Res</th>
                                    <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Oleh</th>
                                    <th class="px-3 py-2 border-r border-slate-700 text-center bg-blue-700">Res</th>
                                    <th class="px-3 py-2 border-r border-slate-700 bg-blue-700">Oleh</th>
                                    <th class="px-3 py-2 border-r border-slate-700 text-center bg-blue-700">Res</th>
                                    <th class="px-3 py-2 border-r border-slate-700 text-center bg-blue-700">Oleh</th>
                                    <th class="px-3 py-2 text-center bg-blue-700">Res</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                                @foreach ($empLogs as $key => $e)
                                    <tr class="even:bg-slate-50 hover:bg-blue-50 transition-colors">
                                        <td class="px-3 py-2 text-center border-r border-gray-50 text-gray-400">
                                            {{ $key + 1 }}</td>
                                        <td class="px-3 py-2 border-r border-gray-50 font-medium">
                                            {{ $e->department_code }}</td>
                                        <td
                                            class="px-4 py-2 border-r border-gray-50 font-semibold text-slate-900 truncate max-w-[150px]">
                                            {{ $e->employee_name }}
                                        </td>
                                        <td class="px-3 py-2 border-r border-gray-50 text-[11px] italic">
                                            {{ $e->position }}</td>
                                        <td
                                            class="px-2 py-2 text-center border-r border-gray-100 font-bold text-slate-700">
                                            {{ $e->total_kpi }}</td>

                                        {{-- Cek 1 --}}
                                        <td class="px-3 py-2 border-r border-gray-50 text-[11px]">
                                            {{ $e->cek1_by ?: '-' }}</td>
                                        <td class="px-3 py-2 border-r border-gray-100 text-center">
                                            @if ($e->cek1_count > 0)
                                                <span
                                                    class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-200 uppercase">
                                                    {{ $e->cek1_count }} OK
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Cek 2 --}}
                                        <td class="px-3 py-2 border-r border-gray-50 text-[11px]">
                                            {{ $e->cek2_by ?: '-' }}</td>
                                        <td class="px-3 py-2 border-r border-gray-100 text-center">
                                            @if ($e->cek2_count > 0)
                                                <span
                                                    class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-200 uppercase">
                                                    {{ $e->cek2_count }} OK
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Approved --}}
                                        <td class="px-3 py-2 border-r border-gray-50 text-[11px]">
                                            {{ $e->app_by ?: '-' }}</td>
                                        <td class="px-3 py-2 border-r border-gray-100 text-center">
                                            @if ($e->app_count > 0)
                                                <span
                                                    class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-200 uppercase">
                                                    {{ $e->app_count }} OK
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Verifikasi --}}
                                        <td class="px-3 py-2 border-r border-gray-50 text-[11px] text-center">
                                            {{ $e->ver_by ?: '-' }}</td>
                                        <td class="px-3 py-2 text-center">
                                            @if ($e->ver_count > 0)
                                                <div class="flex flex-col gap-1 items-center">
                                                    <span
                                                        class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-[10px] font-bold border border-blue-200 uppercase">
                                                        {{ $e->ver_count }} OK
                                                    </span>
                                                    @if ($e->invalid_count > 0)
                                                        <span class="text-[9px] font-bold text-red-500 italic">
                                                            {{ $e->invalid_count }} Invalid
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        {{-- @php
            dd($targetColumn, $targetUnitCount, $targetUnitCountDept, $totalTargetUnitCount, $month, $department->id, 'TU Count', $targetUnitCounts, 'TU Dept Count', $targetUnitCountsDept);
        @endphp --}}
    </div>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable untuk Tabel Dept
            $('#tableDept').DataTable({
                "pageLength": 10, // Menampilkan 10 data per halaman
                "lengthMenu": [5, 10, 25, 50],
                "order": [], // Matikan auto sort saat load karena header kompleks
                "language": {
                    "search": "Cari Dept:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                }
            });

            // Inisialisasi DataTable untuk Tabel Employee
            $('#tableEmp').DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [],
                "language": {
                    "search": "Cari Karyawan:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                }
            });
        });
    </script>
</x-app-layout>
