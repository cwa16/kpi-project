<table>
    <thead>
        <tr>
            <th rowspan="2"
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle; width: 30px;">
                No.</th>
            <th rowspan="2"
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle; width: 150px;">
                Dept</th>
            <th rowspan="2"
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle; width: 100px;">
                NIK</th>
            <th rowspan="2"
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle; width: 250px;">
                Nama</th>
            <th rowspan="2"
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle; width: 150px;">
                Posisi</th>
            <th colspan="2"
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle;">
                KPI Dept (30%)</th>
            <th colspan="2"
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle;">
                KPI Individu (70%)</th>
            <th rowspan="2"
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle; width: 100px;">
                Total</th>
        </tr>
        <tr>
            <th
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle;">
                Full Year</th>
            <th
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle;">
                Result</th>
            <th
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle;">
                Full Year</th>
            <th
                style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff; text-align: center; vertical-align: middle;">
                Result</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 0; @endphp
        @foreach ($employees as $employee)
            @php
                $i++;
                $employeeId = $employee->employee_id;
                $departmentId = $employee->department_id;
                $empRawScore = $sumEmpAnnual[$employeeId] ?? 0;

                // Logic Dept Score (HO/Director)
                if ($departmentId == 11) {
                    $deptRawScore = collect([13, 14, 15, 16, 17, 18])->avg(fn($id) => $sumDeptAnnual[$id] ?? 0);
                } elseif ($departmentId == 21) {
                    $deptRawScore = collect([13, 14, 15])->avg(fn($id) => $sumDeptAnnual[$id] ?? 0);
                } elseif ($departmentId == 22) {
                    $deptRawScore = collect([16, 17, 18])->avg(fn($id) => $sumDeptAnnual[$id] ?? 0);
                } else {
                    $deptRawScore = $sumDeptAnnual[$departmentId] ?? 0;
                }

                $deptWeightedScore = $deptRawScore * 0.3;
                $empWeightedScore = $empRawScore * 0.7;
                $totalScore = $deptWeightedScore + $empWeightedScore;

                // Zebra Striping color
                $bgColor = $i % 2 === 0 ? '#ffffff' : '#e0f2fe';
            @endphp
            <tr>
                <td style="border: 1px solid #000000; background-color: {{ $bgColor }}; text-align: center;">
                    {{ $i }}</td>
                <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">{{ $employee->dept }}</td>
                <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">{{ $employee->nik }}</td>
                <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">{{ $employee->name }}</td>
                <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">
                    {{ $employee->occupation }}</td>

                {{-- Dept --}}
                <td style="border: 1px solid #000000; background-color: {{ $bgColor }}; text-align: center;">
                    {{ number_format($deptRawScore, 1) }}%
                </td>
                <td
                    style="border: 1px solid #000000; background-color: {{ $bgColor }}; text-align: center; color: #6b7280;">
                    {{ number_format($deptWeightedScore, 1) }}%
                </td>

                {{-- Emp --}}
                <td style="border: 1px solid #000000; background-color: {{ $bgColor }}; text-align: center;">
                    {{ number_format($empRawScore, 1) }}%
                </td>
                <td
                    style="border: 1px solid #000000; background-color: {{ $bgColor }}; text-align: center; color: #6b7280;">
                    {{ number_format($empWeightedScore, 1) }}%
                </td>

                {{-- Total --}}
                <td
                    style="border: 1px solid #000000; background-color: {{ $bgColor }}; text-align: center; font-weight: bold;">
                    {{ number_format($totalScore, 1) }}%
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- INACTIVE TABLE --}}
@if ($inactiveTargetEmployees->count() > 0)
    <table>
        <tr></tr>
        <tr></tr> {{-- Spacing --}}
        <tr>
            <td colspan="10" style="font-weight: bold; font-size: 14px;">KPI Sebelumnya (Inactive)</td>
        </tr>
        <thead>
            <tr>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">No.</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">Dept</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">NIK</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">Nama</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">Posisi</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">KPI Dept</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">Bobot</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">KPI Indv</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">Bobot</th>
                <th style="border: 1px solid #000000; background-color: #1d4ed8; color: #ffffff;">Total Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php $j = 0; @endphp
            @foreach ($inactiveTargetEmployees as $employee)
                @php
                    $j++;
                    $empId = $employee->employee_id;
                    $deptId = $employee->department_id;
                    $empRawScore = $sumInactiveEmpAnnual[$empId] ?? 0;

                    if ($deptId == 11) {
                        $deptRawScore = collect([13, 14, 15, 16, 17, 18])->avg(
                            fn($id) => $sumInactiveDeptAnnual[$id] ?? 0,
                        );
                    } elseif ($deptId == 21) {
                        $deptRawScore = collect([13, 14, 15])->avg(fn($id) => $sumInactiveDeptAnnual[$id] ?? 0);
                    } elseif ($deptId == 22) {
                        $deptRawScore = collect([16, 17, 18])->avg(fn($id) => $sumInactiveDeptAnnual[$id] ?? 0);
                    } else {
                        $deptRawScore = $sumInactiveDeptAnnual[$deptId] ?? 0;
                    }

                    $deptWeightedScore = $deptRawScore * 0.3;
                    $empWeightedScore = $empRawScore * 0.7;
                    $totalScore = $deptWeightedScore + $empWeightedScore;
                    $bgColor = $j % 2 === 0 ? '#ffffff' : '#e0f2fe';
                @endphp
                <tr>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">{{ $j }}
                    </td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">
                        {{ $employee->dept }}</td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">{{ $employee->nik }}
                    </td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">
                        {{ $employee->name }}</td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">
                        {{ $employee->occupation }}</td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">
                        {{ number_format($deptRawScore, 1) }}%</td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">
                        {{ number_format($deptWeightedScore, 1) }}%</td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">
                        {{ number_format($empRawScore, 1) }}%</td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }};">
                        {{ number_format($empWeightedScore, 1) }}%</td>
                    <td style="border: 1px solid #000000; background-color: {{ $bgColor }}; font-weight: bold;">
                        {{ number_format($totalScore, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
