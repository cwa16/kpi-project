<style>
    @page {
        size: A4 landscape;
        margin: 1cm 0.7cm 1.5cm 0.7cm;
    }

    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        font-size: 9.5px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        page-break-inside: auto;
    }

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }

    tr {
        page-break-inside: avoid !important;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 4px 5px;
        vertical-align: middle;
        word-wrap: break-word;
    }

    /* ===== TYPOGRAPHY ===== */
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .font-bold {
        font-weight: bold;
    }

    .text-lg {
        font-size: 12px;
    }

    .text-xs {
        font-size: 8px;
    }

    .text-gray {
        color: #4b5563;
    }

    /* ===== COLORS (TETAP SEPERTI ASLI) ===== */
    .bg-blue-header {
        background-color: #1d4ed8;
        color: #ffffff;
    }

    .bg-target {
        background-color: #f0f7ff;
    }

    .bg-gray-even {
        background-color: #ffffff;
    }

    .bg-blue-odd {
        background-color: #f8fafc;
    }

    /* ===== LAYOUT PDF ===== */
    .header-table td {
        border: none;
        padding: 0;
        vertical-align: top;
    }

    .info-table td {
        border: none;
        padding: 2px 3px;
    }

    .page-break {
        page-break-before: always;
    }
</style>


{{-- Header Instansi --}}
<div style="margin-bottom: 10px;">
    <div class="font-bold text-lg text-gray">PT BRIDGESTONE KALIMANTAN PLANTATION</div>

    <div class="text-center" style="margin-top: -15px;">
        <div class="font-bold text-lg text-gray">KPI Report (Employees)</div>
        <div class="text-gray font-bold text-xs">Periode: Semester {{ $semester }} {{ $year }}</div>
    </div>
</div>

{{-- Sisi Kiri & Kanan: Data Karyawan (Menggunakan Tabel untuk PDF Layout) --}}
<table class="header-table" style="margin-bottom: 15px;">
    <tr>
        <td style="width: 50%;">
            <table class="info-table">
                <tr>
                    <td style="width: 25%;" class="font-bold text-gray">Dept</td>
                    <td style="width: 5%;">:</td>
                    <td class="text-gray">{{ $userCreds->department }}</td>
                </tr>
                <tr>
                    <td class="font-bold text-gray">NIK</td>
                    <td>:</td>
                    <td class="text-gray">{{ $userCreds->nik }}</td>
                </tr>
            </table>
        </td>
        <td style="width: 50%;">
            <table class="info-table">
                <tr>
                    <td style="width: 25%;" class="font-bold text-gray">Nama</td>
                    <td style="width: 5%;">:</td>
                    <td class="text-gray">{{ $userCreds->employee }}</td>
                </tr>
                <tr>
                    <td class="font-bold text-gray">Posisi</td>
                    <td>:</td>
                    <td class="text-gray">{{ $userCreds->occupation }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- Tabel Utama KPI --}}
<table>
    <thead>
        <tr>
            <th class="bg-blue-header text-center" style="width: 5%;" rowspan="2">No. KPI</th>
            <th class="bg-blue-header text-center" style="width: 18%;" rowspan="2">KPI</th>
            <th class="bg-blue-header text-center" style="width: 10%;" rowspan="2">Data Pendukung</th>
            <th class="bg-blue-header text-center" style="width: 3%;" rowspan="2">Trend</th>
            <th class="bg-blue-header text-center" style="width: 5%;" rowspan="2">Periode</th>
            <th class="bg-blue-header text-center" style="width: 3%;" rowspan="2">Unit</th>
            <th class="bg-blue-header text-center" style="width: 4%;" rowspan="2">Bobot %</th>
            <th class="bg-blue-header text-center" style="width: 5%;" rowspan="2">Status</th>
            <th class="bg-blue-header text-center" style="width: 41%;" colspan="7">Target & Actual KPI</th>
            <th class="bg-blue-header text-center" style="width: 6%;" rowspan="2">Acv. %</th>
        </tr>
        <tr>
            @php


                $months = [];
                $selectedSemester = $semester;

                if ($selectedSemester == 1) {
                    $months = [
                        '1' => 'Jan',
                        '2' => 'Feb',
                        '3' => 'Mar',
                        '4' => 'Apr',
                        '5' => 'May',
                        '6' => 'Jun',
                    ];
                } else {
                    $months = [
                        '7' => 'Jul',
                        '8' => 'Aug',
                        '9' => 'Sep',
                        '10' => 'Oct',
                        '11' => 'Nov',
                        '12' => 'Dec',
                    ];
                }
            @endphp
            @foreach ($months as $month)
                <th class="bg-blue-header text-center">{{ $month }}</th>
            @endforeach
            <th class="bg-blue-header text-center">Total/Avg</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sumTotalWeightingAchievement = 0;
            $totalWeighting = 0;
        @endphp
        @foreach ($targets as $index => $target)
            @php
                $trend = $target->trend === 'Negatif' ? 'N' : 'P';
                $rowColor = $index % 2 === 0 ? 'bg-gray-even' : 'bg-blue-odd';
                $indicator = $target->indicator;
            @endphp

            <tr class="{{ $rowColor }}">
                <td class="text-center" rowspan="3">{{ $target->code }}</td>
                <td rowspan="3">{{ $indicator }}</td>
                <td rowspan="3">{{ $target->supporting_document }}</td>
                <td class="text-center" rowspan="3">{{ $trend }}</td>
                <td class="text-center" rowspan="3">{{ $target->period }}</td>
                <td class="text-center" rowspan="3">{{ $target->unit }}</td>
                <td class="text-center" rowspan="3">{{ $target->weighting }}</td>
                <td class="bg-target text-center">Target</td>

                @php $sumTarget = 0; @endphp
                @foreach ($months as $num => $name)
                    @php
                        $field = 'target_' . $num;
                        $val = $target->$field;
                        $sumTarget += $val;
                    @endphp
                    <td class="bg-target text-center">
                        {{ $target->unit === '%' ? $val * 100 . '%' : number_format($val, 1) }}
                    </td>
                @endforeach
                <td class="bg-target text-center font-bold">{{ number_format($sumTarget, 1) }}</td>

                @php $achievement = $totals[$indicator]['total_achievement_weight'] ?? 0; @endphp
                <td class="text-center font-bold" rowspan="3">{{ number_format($achievement, 1) }}%</td>
            </tr>

            <tr class="{{ $rowColor }}">
                <td class="text-center">Actual</td>
                @foreach ($months as $num => $name)
                    @php
                        $act = $actuals
                            ->where('kpi_item', $indicator)
                            ->first(fn($item) => \Carbon\Carbon::parse($item->date)->format('m') == $num);
                    @endphp
                    <td class="text-center">{{ $act ? $act->actual : '-' }}</td>
                @endforeach
                <td class="text-center font-bold">{{ number_format($totals[$indicator]['total_actual'] ?? 0, 1) }}</td>
            </tr>

            <tr class="{{ $rowColor }}">
                <td class="text-center">%</td>
                @foreach ($months as $num => $name)
                    @php
                        $act = $actuals
                            ->where('kpi_item', $indicator)
                            ->first(fn($item) => \Carbon\Carbon::parse($item->date)->format('m') == $num);
                    @endphp
                    <td class="text-center">{{ $act ? $act->kpi_percentage : '0%' }}</td>
                @endforeach
                <td class="text-center font-bold">{{ number_format($totals[$indicator]['percentageCalc'] ?? 0, 0) }}%
                </td>
            </tr>

            @php
                $sumTotalWeightingAchievement += $totals[$indicator]['total_achievement_weight'] ?? 0;
                $totalWeighting += floatval($target->weighting);
            @endphp
        @endforeach

        {{-- Baris Total Akhir --}}
        <tr class="bg-blue-header font-bold text-white">
            <td colspan="6" class="text-center">Total</td>
            <td class="text-center">{{ $totalWeighting }}%</td>
            <td colspan="8"></td> {{-- Status(1) + Bulan(6) + Total/Avg(1) = 8 --}}
            <td class="text-center">{{ number_format($sumTotalWeightingAchievement, 1) }}%</td>
        </tr>
    </tbody>
</table>
