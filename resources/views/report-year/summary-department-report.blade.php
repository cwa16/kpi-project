<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-gray-100 border border-gray-200 shadow-md shadow-black/10 rounded-md">
        @php
            $currentYear = Carbon\Carbon::now()->year;
            $startYear = 2024;
            $endYear = $currentYear + 2;
            $yearQuery = request()->query('year', $currentYear);
        @endphp
        <div class="flex justify-between">
            <div class="mb-2">
                <div class="mt-1">
                    <span class="text-gray-600 p-1 text-xl">
                        PT. BRIDGESTONE KALIMANTAN PLANTATION
                    </span>
                </div>
                <div class="">
                    <span class="text-gray-600 p-1 text-2xl font-bold">
                        Summary KPI Department Report (Annual)
                    </span>
                </div>
                <div class="pl-1">
                    <span class="text-gray-600">Periode: {{ request()->query('year') }}</span>
                </div>
            </div>
            <div class="flex justify-end">
                <form action="{{ route('report-year.summaryDept') }}" method="GET">
                    <div class="p-0 flex justify-between gap-x-1">
                        <div class="relative mt-1 rounded-md">
                            <div class="mt-2">
                                <select name="year" id="year"
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="{{ request()->query('year') }}">-- Tahun --</option>
                                    @for ($year = $startYear; $year <= $endYear; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="relative mt-1 rounded-md">
                            <div class="mt-2 mb-0">
                                <select name="department[]" multiple id="department"
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="">-- Department --</option>
                                    @foreach ($allDept as $dept)
                                        <option value="{{ $dept->id }}">
                                            {{ collect(request()->input('department'))->contains($dept->id) ? 'selected' : '' }}
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="relative mt-1 rounded-md">
                            <div class="mt-2 mb-1">
                                <select name="status[]" multiple id="status"
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="">-- Status --</option>
                                    @foreach ($allOccupation as $item)
                                        <option value="{{ $item->status }}">
                                            {{ collect(request()->input('status'))->contains($item->status) ? 'selected' : '' }}
                                            {{ $item->status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="my-2">
                            <button type="submit" class="rounded-md bg-blue-500 text-white p-2">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex justify-end">
            <a href="{{ route('report-year.summaryDept.export', request()->all()) }}"
                class="p-1.5 rounded-md text-white bg-green-500 mb-1 inline-block">
                Export to Excel
            </a>
        </div>
        <div class="p-0">
            <table id="exportTable" class="w-full bg-white table-fixed">
                <thead>
                    <tr>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 3%" rowspan="2">No.</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 10%" rowspan="2">Dept</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 6%" rowspan="2">NIK</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 20%" rowspan="2">Nama</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 12%" rowspan="2">Posisi</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 8%" colspan="2">
                            KPI Dept<br>(30%)
                        </th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 8%" colspan="2">
                            KPI Individu<br>(70%)
                        </th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 12%" rowspan="2">Total</th>

                    </tr>
                    <tr>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 8%">
                            Full Year
                        </th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 8%">
                            Result
                        </th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 8%">
                            Full Year
                        </th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                            style="width: 8%">
                            Result
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                        $startIndex = ($employees->currentPage() - 1) * $employees->perPage() + 1;

                        // Variables for Grand Total Calculation
                        $sumDeptRawAll = 0;
                        $sumDeptWeightedAll = 0;
                        $sumEmpRawAll = 0;
                        $sumEmpWeightedAll = 0;
                        $sumTotalAll = 0;
                        $rowCount = 0;
                    @endphp

                    @foreach ($employees as $index => $employee)
                        @php
                            $i++;
                            $employeeId = $employee->employee_id;
                            $departmentId = $employee->department_id;

                            // --- 1. Get RAW Employee Annual Score ---
                            $empRawScore = $sumEmpAnnual[$employeeId] ?? 0;

                            // --- 2. Get RAW Dept Annual Score (With Logic for HO/Directors) ---
                            if ($departmentId == 11) {
                                // Head Office
                                $deptRawScore = collect([13, 14, 15, 16, 17, 18])->avg(
                                    fn($id) => $sumDeptAnnual[$id] ?? 0,
                                );
                            } elseif ($departmentId == 21) {
                                // Director 1
                                $deptRawScore = collect([13, 14, 15])->avg(fn($id) => $sumDeptAnnual[$id] ?? 0);
                            } elseif ($departmentId == 22) {
                                // Director 2
                                $deptRawScore = collect([16, 17, 18])->avg(fn($id) => $sumDeptAnnual[$id] ?? 0);
                            } else {
                                $deptRawScore = $sumDeptAnnual[$departmentId] ?? 0;
                            }

                            // --- 3. Calculate Weighted Scores ---
                            $deptWeightedScore = $deptRawScore * 0.3;
                            $empWeightedScore = $empRawScore * 0.7;
                            $totalScore = $deptWeightedScore + $empWeightedScore;

                            // Accumulate for Footer
                            $sumDeptRawAll += $deptRawScore;
                            $sumDeptWeightedAll += $deptWeightedScore;
                            $sumEmpRawAll += $empRawScore;
                            $sumEmpWeightedAll += $empWeightedScore;
                            $sumTotalAll += $totalScore;
                            $rowCount++;
                        @endphp

                        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                            {{-- Identity Cols --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                                {{ $startIndex + $index }}
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">
                                {{ $employee->dept }}
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">
                                {{ $employee->nik }}
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">
                                {{ $employee->name }}
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">
                                {{ $employee->occupation }}
                            </td>

                            {{-- KPI Dept Raw --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                                @if ($deptRawScore > 0)
                                    <a href="{{ route('report-year.department', $employee->department_id) }}?semester=1&year={{ $yearQuery }}"
                                        class="hover:underline hover:text-blue-600">
                                        {{ number_format($deptRawScore, 1) }}%
                                    </a>
                                @endif
                            </td>
                            {{-- KPI Dept Weighted (30%) --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center text-gray-500">
                                {{ number_format($deptWeightedScore, 1) }}%
                            </td>

                            {{-- KPI Individu Raw --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                                @if ($empRawScore > 0)
                                    <a href="{{ route('report-year.show', $employee->employee_id) }}?semester=1&year={{ $yearQuery }}"
                                        class="hover:underline hover:text-blue-600">
                                        {{ number_format($empRawScore, 1) }}%
                                    </a>
                                @endif
                            </td>
                            {{-- KPI Individu Weighted (70%) --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center text-gray-500">
                                {{ number_format($empWeightedScore, 1) }}%
                            </td>

                            {{-- Total Score --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center font-bold">
                                {{ number_format($totalScore, 1) }}%
                            </td>
                        </tr>
                    @endforeach

                    {{-- Footer Row (Averages) --}}
                    @if (request()->query('department') && request()->query('status') == '')
                        @php
                            $avgDeptRaw = $rowCount > 0 ? $sumDeptRawAll / $rowCount : 0;
                            $avgDeptWeighted = $rowCount > 0 ? $sumDeptWeightedAll / $rowCount : 0;
                            $avgEmpRaw = $rowCount > 0 ? $sumEmpRawAll / $rowCount : 0;
                            $avgEmpWeighted = $rowCount > 0 ? $sumEmpWeightedAll / $rowCount : 0;
                            $avgTotal = $rowCount > 0 ? $sumTotalAll / $rowCount : 0;
                        @endphp
                        <tr class="bg-gray-200">
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FFF2F2F2"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FFF2F2F2"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"
                                colspan="4">Total Rata-Rata</td>

                            {{-- Dept Avg --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FFF2F2F2"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center font-bold">
                                {{ number_format($avgDeptRaw, 1) }}%
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FFF2F2F2"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center text-gray-500 font-bold">
                                {{ number_format($avgDeptWeighted, 1) }}%
                            </td>

                            {{-- Emp Avg --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FFF2F2F2"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center font-bold">
                                {{ number_format($avgEmpRaw, 1) }}%
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FFF2F2F2"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center text-gray-500 font-bold">
                                {{ number_format($avgEmpWeighted, 1) }}%
                            </td>

                            {{-- Total Avg --}}
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FFF2F2F2"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center font-bold">
                                {{ number_format($avgTotal, 1) }}%
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- INACTIVE KPI SECTION --}}
        @if (request()->query('department') && request()->query('status') == '')
            <div class="flex justify-between mt-2">
                <div class="mt-2">
                    <span class="text-gray-600 p-1 text-2xl font-bold">
                        KPI Sebelumnya (Inactive)
                    </span>
                </div>
                @if (request()->query('department'))
                    <div class="flex justify-end">
                        <button id="exportBtn2" class="p-1.5 rounded-md text-white bg-green-500 my-2">Export</button>
                    </div>
                @endif
            </div>

            <div class="mt-2">
                <table id="exportTable2" class="w-full table-fixed">
                    <thead>
                        <tr>
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 3%">No.</th>
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 10%">Dept</th>
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 6%">NIK</th>
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 20%">Nama</th>
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 12%">Posisi</th>

                            {{-- KPI DEPT COLUMNS --}}
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 8%">
                                KPI Dept<br>(100%)
                            </th>
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 8%">
                                Bobot<br>(30%)
                            </th>

                            {{-- KPI INDIVIDU COLUMNS --}}
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 8%">
                                KPI Indv<br>(100%)
                            </th>
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 8%">
                                Bobot<br>(70%)
                            </th>

                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                                class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700"
                                style="width: 8%">
                                Total Akhir
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $iInactive = 0;
                            // Reset Vars for Inactive
                            $sumInacDeptRawAll = 0;
                            $sumInacDeptWeightedAll = 0;
                            $sumInacEmpRawAll = 0;
                            $sumInacEmpWeightedAll = 0;
                            $sumInacTotalAll = 0;
                            $inactiveRowCount = 0;
                        @endphp

                        @foreach ($inactiveTargetEmployees as $index => $employee)
                            @php
                                $iInactive++;
                                $empId = $employee->employee_id;
                                $deptId = $employee->department_id;

                                // --- 1. Get Inactive RAW Scores ---
                                $empRawScore = $sumInactiveEmpAnnual[$empId] ?? 0;

                                // --- 2. Get Inactive RAW Dept Score ---
                                if ($deptId == 11) {
                                    $deptRawScore = collect([13, 14, 15, 16, 17, 18])->avg(
                                        fn($id) => $sumInactiveDeptAnnual[$id] ?? 0,
                                    );
                                } elseif ($deptId == 21) {
                                    $deptRawScore = collect([13, 14, 15])->avg(
                                        fn($id) => $sumInactiveDeptAnnual[$id] ?? 0,
                                    );
                                } elseif ($deptId == 22) {
                                    $deptRawScore = collect([16, 17, 18])->avg(
                                        fn($id) => $sumInactiveDeptAnnual[$id] ?? 0,
                                    );
                                } else {
                                    $deptRawScore = $sumInactiveDeptAnnual[$deptId] ?? 0;
                                }

                                $deptWeightedScore = $deptRawScore * 0.3;
                                $empWeightedScore = $empRawScore * 0.7;
                                $totalInactiveScore = $deptWeightedScore + $empWeightedScore;

                                // Accumulate
                                $sumInacDeptRawAll += $deptRawScore;
                                $sumInacDeptWeightedAll += $deptWeightedScore;
                                $sumInacEmpRawAll += $empRawScore;
                                $sumInacEmpWeightedAll += $empWeightedScore;
                                $sumInacTotalAll += $totalInactiveScore;

                                $inactiveRowCount++;
                            @endphp
                            <tr class="{{ $iInactive % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                                    {{ $startIndex + $index }}</td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">
                                    {{ $employee->dept }}</td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">
                                    {{ $employee->nik }}</td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">
                                    {{ $employee->name }}
                                </td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">
                                    {{ $employee->occupation }}</td>

                                {{-- DEPT SCORES --}}
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                                    {{ number_format($deptRawScore, 1) }}%
                                </td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center text-gray-500">
                                    {{ number_format($deptWeightedScore, 1) }}%
                                </td>

                                {{-- EMP SCORES --}}
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                                    {{ number_format($empRawScore, 1) }}%
                                </td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center text-gray-500">
                                    {{ number_format($empWeightedScore, 1) }}%
                                </td>

                                {{-- TOTAL --}}
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $iInactive % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center font-bold">
                                    {{ number_format($totalInactiveScore, 1) }}%
                                </td>
                            </tr>
                        @endforeach

                        @if (request()->query('department'))
                            @php
                                $avgInacDeptRaw = $inactiveRowCount > 0 ? $sumInacDeptRawAll / $inactiveRowCount : 0;
                                $avgInacDeptWeighted =
                                    $inactiveRowCount > 0 ? $sumInacDeptWeightedAll / $inactiveRowCount : 0;
                                $avgInacEmpRaw = $inactiveRowCount > 0 ? $sumInacEmpRawAll / $inactiveRowCount : 0;
                                $avgInacEmpWeighted =
                                    $inactiveRowCount > 0 ? $sumInacEmpWeightedAll / $inactiveRowCount : 0;
                                $avgInacTotal = $inactiveRowCount > 0 ? $sumInacTotalAll / $inactiveRowCount : 0;
                            @endphp
                            <tr class="bg-gray-200">
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="FFF2F2F2"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                                </td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="FFF2F2F2"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"
                                    colspan="4">Total</td>

                                {{-- Inactive Dept Avg --}}
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="FFF2F2F2"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center font-bold">
                                    {{ number_format($avgInacDeptRaw, 1) }}%
                                </td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="FFF2F2F2"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center text-gray-500 font-bold">
                                    {{ number_format($avgInacDeptWeighted, 1) }}%
                                </td>

                                {{-- Inactive Emp Avg --}}
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="FFF2F2F2"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center font-bold">
                                    {{ number_format($avgInacEmpRaw, 1) }}%
                                </td>
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="FFF2F2F2"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center text-gray-500 font-bold">
                                    {{ number_format($avgInacEmpWeighted, 1) }}%
                                </td>

                                {{-- Inactive Total Avg --}}
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="FFF2F2F2"
                                    class="border border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center font-bold">
                                    {{ number_format($avgInacTotal, 1) }}%
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Pagination --}}
        <div class="shadow-lg shadow-black/15 mb-2 mt-3">
            <div
                class="flex w-full items-center justify-between border-t border-gray-200 bg-white px-10 py-3 rounded-md">
                <div class="flex flex-1 justify-between sm:hidden">
                    {{ $employees->links() }}
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $employees->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $employees->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $employees->total() }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- end of pagination --}}
    </div>
</x-app-layout>

<script type="text/javascript" src="{{ asset('js/tableToExcel.js') }}"></script>

<script>
    let button = document.getElementById("exportBtn");
    let button2 = document.getElementById("exportBtn2");

    if (button) {
        button.addEventListener("click", e => {
            let table = document.querySelector("#exportTable");
            TableToExcel.convert(table, {
                name: "summary-kpi-employee-report-annual.xlsx",
                sheet: {
                    name: "Annual Report"
                }
            });
        });
    }

    if (button2) {
        button2.addEventListener("click", e => {
            let table = document.querySelector("#exportTable2");
            TableToExcel.convert(table, {
                name: "summary-kpi-employee-report-inactive-annual.xlsx",
                sheet: {
                    name: "Inactive Report"
                }
            });
        });
    }
</script>
