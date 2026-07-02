<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-gray-100 border border-gray-200 shadow-md shadow-black/10 rounded-md">
        @php
            $role = auth()->user()->role;
            $user = auth()->user()->name;
            $email = auth()->user()->email;
            $dept = auth()->user()->department_id;
            $year = request()->query('year');

            // Langsung definisikan 12 bulan tanpa pembagian semester
            $months = [
                '1' => 'Jan',
                '2' => 'Feb',
                '3' => 'Mar',
                '4' => 'Apr',
                '5' => 'May',
                '6' => 'Jun',
                '7' => 'Jul',
                '8' => 'Aug',
                '9' => 'Sep',
                '10' => 'Oct',
                '11' => 'Nov',
                '12' => 'Dec',
            ];
        @endphp

        <div class="p-1">
            <span class="text-gray-600 font-bold text-lg">PT BRIDGESTONE KALIMANTAN PLANTATION</span>
        </div>

        <div class="justify-center flex flex-col items-center">
            <div>
                <span class="text-gray-600 font-bold text-lg text-center">KPI Report (Dept)</span>
            </div>
            <div>
                <span class="text-gray-600 font-bold text-xs text-center">Tahun {{ $year ?? date('Y') }}</span>
            </div>
            <div>
                <span id="departmentName" class="text-gray-600 font-bold text-xs text-center">Divisi:
                    {{ $departmentCreds->name }}</span>
            </div>
        </div>

        <div class="flex justify-between mr-1 items-center mt-4">
            <div class="mx-2 flex gap-x-2">
                <button class="p-1.5 rounded-md text-white bg-blue-500" onclick="history.back();">Back</button>
                @if ($role != 'Inputer' && $role != '')
                    <button id="open-batch-modal" class="p-1.5 rounded-md text-white bg-blue-600">Batch Approve</button>
                @endif
            </div>

            <div class="flex justify-end items-center gap-x-2">
                <!-- Form filter disederhanakan hanya untuk Tahun jika diperlukan -->
                <form action="{{ route('report.department', $departmentCreds->id) }}" method="GET"
                    class="flex items-center m-0">
                    <!-- Jika ada filter tahun, bisa di-unhide tipe input di bawah ini: -->
                    <!-- <input type="number" name="year" id="year" value="{{ $year ?? date('Y') }}" class="appearance-none rounded-md py-1 pl-3 pr-3 text-sm text-gray-500 border-gray-300"> -->
                    <input type="hidden" name="year" id="year" value="{{ $year }}">
                    <button type="submit"
                        class="py-1.5 px-3 bg-blue-600 rounded-md text-white ml-2 hover:bg-blue-700">Refresh
                        Data</button>
                </form>
                <div class="mx-2">
                    <button id="exportBtn"
                        class="p-1.5 px-3 rounded-md text-white bg-green-500 hover:bg-green-600">Export</button>
                </div>
            </div>
        </div>

        <div class="p-1 mt-1">
            <table id="exportTable" class="w-full table-auto">
                <thead>
                    <tr>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 3%;"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700 exclude-from-export"
                            rowspan="2">
                            <input id="select-all" type="checkbox"
                                class="appearance-none w-4 h-4 border border-gray-400 rounded-sm bg-white text-green-500"
                                {{ $role == 'Inputer' || $role == '' ? 'disabled' : '' }}>
                        </th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 3%;"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            rowspan="2">No. KPI</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 25%"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            rowspan="2">KPI</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 3%"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            rowspan="2">Trend</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 4%"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            rowspan="2">Periode Review</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 3%"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            rowspan="2">Unit</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 4%"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            rowspan="2">Bobot "%"</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 6%"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            rowspan="2"></th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 40%"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            colspan="{{ count($months) + 1 }}">Target & Actual KPI</th>
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700"
                            rowspan="2">Bobot Pencapaian</th>
                    </tr>
                    <tr>
                        @foreach ($months as $month)
                            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 7%"
                                class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700">
                                {{ $month }}</th>
                        @endforeach
                        <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 7%"
                            class="border border-gray-400 text-[12px] tracking-wide font-medium text-white py-1 px-0.5 bg-blue-700">
                            Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                        $sumTotalWeightingAchievement = 0;
                    @endphp
                    @foreach ($targets as $target)
                        @php
                            $i++;
                            $rowColor = $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF';
                            $rowClass = $i % 2 === 0 ? 'bg-blue-100' : 'bg-gray-50';
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}" name="selected_targets[]"
                                class="border border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-1 text-center exclude-from-export"
                                rowspan="4">
                                <input id="selected-item-{{ $target->id }}" type="checkbox"
                                    class="selected-item appearance-none w-4 h-4 border border-gray-400 rounded-sm bg-white text-green-500"
                                    data-code="{{ $target->code }}"
                                    {{ $role == 'Inputer' || $role == '' ? 'disabled' : '' }}>
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-1 text-center"
                                rowspan="4">{{ $target->code }}</td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="relative border border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-2 group"
                                rowspan="4">
                                {{ $target->indicator }}
                                <div
                                    class="absolute left-0 bottom-full mb-2 hidden group-hover:block w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg z-10">
                                    {{ $target->detail }}
                                </div>
                            </td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-2 text-center"
                                rowspan="4">{{ $target->period }}</td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border border-gray-400 text-[10px] tracking-wide font-medium text-center text-gray-600 py-0 px-2"
                                rowspan="4">{{ $target->trend }}</td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border text-center border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-2"
                                rowspan="4">{{ $target->unit }}</td>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border text-center border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-2"
                                rowspan="4">{{ $target->weighting }}</td>

                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                Target</td>

                            @php $sumTarget = 0; @endphp

                            @foreach ($months as $month => $monthName)
                                @php
                                    $targetUnitField = 'target_' . $month;
                                    $targetUnit = $target->$targetUnitField;
                                    $sumTarget += $targetUnit;
                                    $actual = $actuals->first(function ($item) use ($target, $month) {
                                        return \Carbon\Carbon::parse($item->date)->format('m') == $month &&
                                            $item->kpi_code == $target->code;
                                    });
                                @endphp
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                    @if ($actual)
                                        @if ($actual->kpi_unit === '%')
                                            {{ $actual->target !== null ? $actual->target . '%' : '' }}
                                        @elseif ($actual->kpi_unit == 'Rp')
                                            {{ $actual->target !== null ? substr(number_format($actual->target, 0, '.', ','), 0, 7) : '' }}
                                        @elseif ($actual->kpi_unit == 'Kg')
                                            {{ $actual->target !== null ? substr(number_format($actual->target, 1, '.', ','), 0, 7) : '' }}
                                        @else
                                            {{ $actual->target !== null ? $actual->target : 'N/A' }}
                                        @endif
                                    @else
                                        @if ($target->unit === '%')
                                            {{ $targetUnit !== null ? $targetUnit . '%' : 'N/A' }}
                                        @elseif ($target->unit == 'Rp')
                                            {{ $targetUnit !== null ? substr(number_format($targetUnit, 0, '.', ','), 0, 7) : 'N/A' }}
                                        @elseif ($target->unit == 'Kg')
                                            {{ $targetUnit !== null ? substr(number_format($targetUnit, 1, '.', ','), 0, 7) : 'N/A' }}
                                        @else
                                            {{ $targetUnit !== null ? $targetUnit : 'N/A' }}
                                        @endif
                                    @endif
                                </td>
                            @endforeach

                            @php $totalTarget = $sumTarget; @endphp

                            @if ($totalTarget >= 0)
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                    @if ($target->unit === '%')
                                        {{ $totalTarget }}%
                                    @elseif ($target->unit === 'Rp')
                                        {{ substr(number_format($totalTarget, 0, '.', ','), 0, 7) }}
                                    @elseif ($target->unit === 'Kg')
                                        {{ substr(number_format($totalTarget, 0, '.', ','), 0, 7) }}
                                    @else
                                        {{ $totalTarget }}
                                    @endif
                                </td>
                            @else
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                </td>
                            @endif

                            @php
                                $totalWeightingAchievement = $totals[$target->code]['total_achievement_weight'] ?? 0;
                                $sumTotalWeightingAchievement += $totalWeightingAchievement;
                            @endphp

                            @if ($totalWeightingAchievement >= 0)
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center"
                                    rowspan="4">{{ number_format($totalWeightingAchievement, 1) }}%</td>
                            @else
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center"
                                    rowspan="4"></td>
                            @endif
                        </tr>

                        <tr>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border bg-gray-50 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                Actual</td>
                            @foreach ($months as $month => $monthName)
                                @php
                                    $actual = $actuals->first(function ($item) use ($target, $month) {
                                        return \Carbon\Carbon::parse($item->date)->format('m') == $month &&
                                            $item->kpi_code == $target->code;
                                    });
                                @endphp
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-gray-50 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                    @if ($target->unit === '%')
                                        {{ $actual ? $actual->actual . '%' : '' }}
                                    @elseif ($target->unit == 'Rp')
                                        {{ $actual ? substr(number_format($actual->actual, 0, '.', ','), 0, 7) : '' }}
                                    @elseif ($target->unit == 'Kg')
                                        {{ $actual ? substr(number_format($actual->actual, 0, '.', ','), 0, 7) : '' }}
                                    @else
                                        {{ $actual ? $actual->actual : '' }}
                                    @endif
                                </td>
                            @endforeach
                            @php $totalActual = $totals[$target->code]['total_actual'] ?? 0; @endphp
                            @if ($totalTarget >= 0)
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-gray-50 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                    @if ($target->unit === '%')
                                        {{ number_format($totalActual, 0) }}%
                                    @elseif ($target->unit === 'Tgl' || $target->unit === 'tgl')
                                        {{ number_format($totalActual) }}
                                    @elseif ($target->unit === 'Rp')
                                        {{ substr(number_format($totalActual, 0, '.', ','), 0, 7) }}
                                    @elseif ($target->unit === 'Kg')
                                        {{ substr(number_format($totalActual, 0, '.', ','), 0, 7) }}
                                    @else
                                        {{ $totalActual }}
                                    @endif
                                </td>
                            @else
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-gray-50 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                </td>
                            @endif
                        </tr>

                        <tr>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                %</td>
                            @foreach ($months as $month => $monthName)
                                @php
                                    $actual = $actuals->first(function ($item) use ($target, $month) {
                                        return \Carbon\Carbon::parse($item->date)->format('m') == $month &&
                                            $item->kpi_code == $target->code;
                                    });
                                @endphp
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                    {{ $actual ? $actual->kpi_percentage : '' }}</td>
                            @endforeach
                            @php $totalPercentage = $totals[$target->code]['percentageCalc'] ?? 0; @endphp
                            @if ($totalTarget >= 0)
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                    {{ number_format($totalPercentage) }}%</td>
                            @else
                                <td
                                    class="border bg-blue-100 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                                </td>
                            @endif
                        </tr>

                        <tr>
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="relative border bg-gray-50 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center group">
                                Rekaman
                                <div
                                    class="absolute left-0 bottom-full mb-2 hidden group-hover:block w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg z-10">
                                    {{ $target->supporting_document }}
                                </div>
                            </td>
                            @foreach ($months as $month => $monthName)
                                @php
                                    $actual = $actuals->first(function ($item) use ($target, $month) {
                                        return \Carbon\Carbon::parse($item->date)->format('m') == $month &&
                                            $item->kpi_code == $target->code;
                                    });
                                @endphp
                                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                    data-fill-color="{{ $rowColor }}"
                                    class="border bg-gray-50 border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center hover:underline">
                                    @if ($actual)
                                        @if ($actual->record_file)
                                            @php
                                                $modalId = 'modal-' . $actual->department_actual_id;
                                                $buttonId = 'open-modal-' . $actual->department_actual_id;
                                                $backgroundId = 'modal-background-' . $actual->department_actual_id;
                                                $date = \Carbon\Carbon::parse($actual->date);
                                                $prevButtonId = 'prevButton-' . $actual->department_actual_id;
                                                $nextButtonId = 'nextButton-' . $actual->department_actual_id;
                                                $pdfObjectId = 'pdfObject-' . $actual->department_actual_id;
                                            @endphp
                                            <button id="{{ $buttonId }}" class="hover:underline"
                                                data-month="{{ $date->format('m') }}"
                                                data-actual-id="{{ $actual->department_actual_id }}">
                                                @if ($actual->status == 'Invalid')
                                                    <span class="text-red-500">Invalid</span>
                                                @elseif ($actual->status == 'Revise')
                                                    <span class="text-orange-600">Revisi</span>
                                                @elseif ($actual->approved_at != null)
                                                    <span class="text-green-500">Yes</span>
                                                @elseif ($actual->mng_approved_at != null)
                                                    <span class="text-blue-500">Review</span>
                                                @elseif ($actual->checked_at != null)
                                                    <span class="text-indigo-700">Check 2</span>
                                                @elseif ($actual->asst_mng_checked_at != null)
                                                    <span class="text-orange-300">Check 1</span>
                                                @elseif ($actual->input_at != null)
                                                    <span class="text-yellow-500">Check</span>
                                                @endif
                                            </button>

                                            {{-- MODAL --}}
                                            <div id="{{ $backgroundId }}"
                                                class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden exclude-from-export">
                                            </div>
                                            <div id="{{ $modalId }}"
                                                class="modal fixed inset-0 justify-center hidden exclude-from-export z-50 mt-5"
                                                data-month="{{ $date->format('m') }}">
                                                <div class="flex justify-center">
                                                    <div
                                                        class="bg-gray-50 rounded-lg shadow-lg px-4 py-2 w-1/2 max-h-[750px] overflow-y-auto">
                                                        <div class="flex justify-end">
                                                            <button id="close-modal-{{ $modalId }}"
                                                                class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <h2 class="text-xl font-bold mb-0.5">Review Data Pendukung
                                                            </h2>
                                                            <span
                                                                class="text-[12px] tracking-wide font-medium text-gray-600 mb-0.5">Bulan:
                                                                {{ $monthName }}</span>

                                                            <div><span
                                                                    id="fileNumber-modal-{{ $actual->department_actual_id }}"
                                                                    class="text-[12px] tracking-wide font-medium text-gray-600 mb-1"></span>
                                                            </div>
                                                            <div class="p-0">
                                                                <span
                                                                    class="text-[12px] tracking-wide font-medium text-gray-600 mb-1">Komentar:</span>
                                                                <span
                                                                    id="comment-modal-{{ $actual->department_actual_id }}"
                                                                    class="text-[12px] tracking-wide font-medium text-gray-600 mb-1"></span>
                                                            </div>
                                                            <div class="p-0 flex gap-x-4 justify-center">
                                                                <div><span
                                                                        class="text-[12px] tracking-wide font-medium text-gray-600 mb-1">T:</span>
                                                                    <span
                                                                        id="t-modal-{{ $actual->department_actual_id }}"
                                                                        class="text-[12px] tracking-wide font-medium text-gray-600 mb-1"></span>
                                                                </div>
                                                                <div><span
                                                                        class="text-[12px] tracking-wide font-medium text-gray-600 mb-1">A:</span>
                                                                    <span
                                                                        id="a-modal-{{ $actual->department_actual_id }}"
                                                                        class="text-[12px] tracking-wide font-medium text-gray-600 mb-1"></span>
                                                                </div>
                                                                <div><span
                                                                        class="text-[12px] tracking-wide font-medium text-gray-600 mb-1">%:</span>
                                                                    <span
                                                                        id="percent-modal-{{ $actual->department_actual_id }}"
                                                                        class="text-[12px] tracking-wide font-medium text-gray-600 mb-1"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="p-1 flex justify-between">
                                                            <button id="{{ $prevButtonId }}"
                                                                class="bg-blue-500 text-white p-2 text-[12px] rounded">Previous</button>
                                                            <button id="{{ $nextButtonId }}"
                                                                class="bg-blue-500 text-white p-2 text-[12px] rounded">Next</button>
                                                        </div>

                                                        <div class="pdfViewer mt-1">
                                                            <object id="{{ $pdfObjectId }}" type="application/pdf"
                                                                width="100%" height="400px"></object>
                                                        </div>

                                                        <div id="checkbox-container-{{ $modalId }}"
                                                            class="p-1 grid grid-cols-4">
                                                            @php
                                                                $userNik = auth()->user()->nik;
                                                                $canCheck1 = \App\Models\ApprovalMatrix::where(
                                                                    'employee_nik',
                                                                    $userNik,
                                                                )
                                                                    ->where('kpi_name', $target->indicator)
                                                                    ->where('approval_type', 'Check 1')
                                                                    ->exists();
                                                                $canCheck2 = \App\Models\ApprovalMatrix::where(
                                                                    'employee_nik',
                                                                    $userNik,
                                                                )
                                                                    ->where('kpi_name', $target->indicator)
                                                                    ->where('approval_type', 'Check 2')
                                                                    ->exists();
                                                                $canMngApprove = \App\Models\ApprovalMatrix::where(
                                                                    'employee_nik',
                                                                    $userNik,
                                                                )
                                                                    ->where('kpi_name', $target->indicator)
                                                                    ->where('approval_type', 'Mng Approve')
                                                                    ->exists();
                                                                $canApproveFinal = \App\Models\ApprovalMatrix::where(
                                                                    'employee_nik',
                                                                    $userNik,
                                                                )
                                                                    ->where('kpi_name', $target->indicator)
                                                                    ->where('approval_type', 'Approver')
                                                                    ->exists();
                                                            @endphp

                                                            <div class="p-0">
                                                                <label class="text-[14px]">
                                                                    <input type="checkbox" class="status-checkbox"
                                                                        data-actual-id="{{ $actual->department_actual_id }}"
                                                                        data-status="Checked 1"
                                                                        {{ $actual->asst_mng_checked_at ? 'checked' : '' }}
                                                                        {{ $role == 'Checker 1' || $role == 'Checker WS' || $role == 'Checker Factory' || $role == 'FAD' || $email == 'widya.citra@bskp.co.id' || $canCheck1 ? '' : 'disabled' }}>
                                                                    Check 1
                                                                </label>
                                                                <div class="flex justify-center gap-x-2 mt-1.5">
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[9px] text-center">Check 1
                                                                            By:</span>
                                                                        <span
                                                                            class="text-[9px] text-center {{ !$actual->asst_mng_checked_by ? 'text-red-500' : '' }}">{{ $actual->asst_mng_checked_by ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[9px] text-center">Check 1
                                                                            At:</span>
                                                                        <span
                                                                            class="text-[9px] text-center {{ !$actual->asst_mng_checked_at ? 'text-red-500' : '' }}">{{ $actual->asst_mng_checked_at ? \Carbon\Carbon::parse($actual->asst_mng_checked_at)->format('d M Y H:i') : 'N/A' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="p-0">
                                                                <label class="text-[14px]">
                                                                    <input type="checkbox" class="status-checkbox"
                                                                        data-actual-id="{{ $actual->department_actual_id }}"
                                                                        data-status="Checked 2"
                                                                        {{ $actual->checked_by ? 'checked' : '' }}
                                                                        {{ $role == 'Checker Div 1' || ($role == 'Checker Div 2' && $actual->status == 'Checked 1') || $canCheck2 ? '' : 'disabled' }}>
                                                                    Check 2
                                                                </label>
                                                                <div class="flex justify-center gap-x-2 mt-1.5">
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[9px] text-center">Check 2
                                                                            By:</span>
                                                                        <span
                                                                            class="text-[9px] text-center {{ !$actual->checked_by ? 'text-red-500' : '' }}">{{ $actual->checked_by ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[9px] text-center">Check 2
                                                                            At:</span>
                                                                        <span
                                                                            class="text-[9px] text-center {{ !$actual->checked_at ? 'text-red-500' : '' }}">{{ $actual->checked_at ? \Carbon\Carbon::parse($actual->checked_at)->format('d M Y H:i') : 'N/A' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="p-0">
                                                                <label class="text-[14px]">
                                                                    <input type="checkbox" class="status-checkbox"
                                                                        data-actual-id="{{ $actual->department_actual_id }}"
                                                                        data-status="Mng Approve"
                                                                        {{ $actual->mng_approved_at ? 'checked' : '' }}
                                                                        {{ $role == 'Mng Approver' || $canMngApprove ? '' : 'disabled' }}>
                                                                    Approved (Mng)
                                                                </label>
                                                                <div class="flex justify-center gap-x-2 mt-1.5">
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[9px] text-center">Approved
                                                                            By:</span>
                                                                        <span
                                                                            class="text-[9px] text-center {{ !$actual->mng_approved_by ? 'text-red-500' : '' }}">{{ $actual->mng_approved_by ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[9px] text-center">Approved
                                                                            At:</span>
                                                                        <span
                                                                            class="text-[9px] text-center {{ !$actual->mng_approved_at ? 'text-red-500' : '' }}">{{ $actual->mng_approved_at ? \Carbon\Carbon::parse($actual->mng_approved_at)->format('d M Y H:i') : 'N/A' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="p-0">
                                                                <label class="text-[14px]">
                                                                    <input type="checkbox" class="status-checkbox"
                                                                        data-actual-id="{{ $actual->department_actual_id }}"
                                                                        data-status="Approved"
                                                                        {{ $actual->approved_at ? 'checked' : '' }}
                                                                        {{ $role == 'Approver' || $canApproveFinal ? '' : 'disabled' }}>
                                                                    Final Check
                                                                </label>
                                                                <div class="flex justify-center gap-x-2 mt-1.5">
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[9px] text-center">Final
                                                                            Check By:</span>
                                                                        <span
                                                                            class="text-[9px] text-center {{ !$actual->approved_by ? 'text-red-500' : '' }}">{{ $actual->approved_by ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[9px] text-center">Final
                                                                            Check At:</span>
                                                                        <span
                                                                            class="text-[9px] text-center {{ !$actual->approved_at ? 'text-red-500' : '' }}">{{ $actual->approved_at ? \Carbon\Carbon::parse($actual->approved_at)->format('d M Y H:i') : 'N/A' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if ($role != 'Inputer' && $role != '')
                                                            @if ($actual->status !== 'Approved')
                                                                <div class="p-1 flex justify-start gap-x-2">
                                                                    <span
                                                                        class="text-semibold mb-1 text-[12px]">Berikan
                                                                        Komentar</span>
                                                                    @if ($role == 'Approver' || $canApproveFinal)
                                                                        <div class="flex gap-x-2 items-center">
                                                                            <div>
                                                                                <input type="checkbox"
                                                                                    name="is_invalid"
                                                                                    id="is_invalid_{{ $actual->department_actual_id }}">
                                                                            </div>
                                                                            <span>Data Pendukung Invalid</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <form action="{{ route('email.sendEmailDept') }}"
                                                                    method="POST"
                                                                    id="send-email-form-{{ $actual->department_actual_id }}">
                                                                    @csrf
                                                                    <div class="p-0 mb-2 flex justify-center">
                                                                        <textarea name="comment" id="comment-{{ $actual->department_actual_id }}" cols="40" rows="2"></textarea>
                                                                    </div>
                                                                    <div class="flex justify-center gap-3">
                                                                        <div class="flex flex-col">
                                                                            <button
                                                                                class="bg-yellow-500 text-white px-4 py-2 rounded text-[12px] mb-3">
                                                                                <i class="ri-send-plane-line"></i>
                                                                                <span>Kirim Revisi</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="kpi_code"
                                                                        value="{{ $target->code }}">
                                                                    <input type="hidden" name="kpi_item"
                                                                        value="{{ $target->indicator }}">
                                                                    <input type="hidden" name="department_id"
                                                                        value="{{ $target->department_id }}">
                                                                    <input type="hidden" name="actual_id"
                                                                        value="{{ $actual->department_actual_id }}">
                                                                </form>

                                                                <form action="{{ route('report.setInvalidDept') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="department_actual_id"
                                                                        value="{{ $actual->department_actual_id }}">
                                                                    <div class="hidden"
                                                                        id="invalid-comment-{{ $actual->department_actual_id }}">
                                                                        <div class="p-1 flex justify-start">
                                                                            <span
                                                                                class="text-semibold mb-1 text-[12px]">Komentar
                                                                                data pendukung tidak valid:</span>
                                                                        </div>
                                                                        <div
                                                                            class="p-0 mb-2 flex justify-center gap-x-2">
                                                                            <textarea name="invalid_reason" id="invalid_reason-{{ $actual->department_actual_id }}" cols="40"
                                                                                rows="2"></textarea>
                                                                        </div>
                                                                        <button type="submit"
                                                                            id="set-invalid-btn-{{ $actual->department_actual_id }}"
                                                                            class="bg-red-500 text-white px-4 py-2 rounded text-[12px] mb-3 hidden">
                                                                            <i class="ri-close-line"></i>
                                                                            <span>Set Data Invalid</span>
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-red-500">No</span>
                                        @endif
                                    @else
                                        <span></span>
                                    @endif
                                </td>
                            @endforeach
                            <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                                data-fill-color="{{ $rowColor }}"
                                class="border border-gray-400 text-[10px] tracking-wide font-medium text-gray-600 py-0 px-0.5 text-center">
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border bg-blue-500 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0 px-0.5 text-center changeColSpan"
                            colspan="6">Total</td>
                        <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border bg-blue-500 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0 px-0.5 text-center">
                            {{ $sumWeighting }}%</td>
                        <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border bg-blue-500 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0 px-0.5 text-center"
                            colspan="{{ count($months) + 2 }}"></td>
                        <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true"
                            data-fill-color="FF0066FF" data-f-color="FFFFFFFF"
                            class="border bg-blue-500 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0 px-0.5 text-center"
                            colspan="1">{{ number_format($sumTotalWeightingAchievement, 1) }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bagian KPI Sebelumnya saya potong di sini untuk keterbacaan, strukturnya sama persis dengan tabel utama namun melooping $inactiveTargets -->

    </div>

    {{-- Modal Batch --}}
    <div id="batch-modal-bg" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden"></div>
    <div id="batch-modal" class="fixed inset-0 justify-center hidden">
        <div class="flex justify-center">
            <div
                class="bg-gray-50 rounded-lg shadow-lg px-4 py-2 w-[500px] max-h-[750px] overflow-y-auto mt-52 items-center">
                <div class="flex justify-end">
                    <button id="close-batch-modal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                <div class="mt-1">
                    <div class="mt-0 py-3 px-0.5">
                        <div><span class="mb-0.5 font-semibold">Pilih Bulan</span></div>
                        <form id="batch-approve-form"
                            action="{{ route('actual.batchUpdateActualDept', $departmentCreds->id) }}"
                            method="POST" class="flex gap-x-3 p-0">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="year" id="year_batch"
                                value="{{ request()->query('year') }}">
                            <input type="hidden" name="selected_targets" id="selected_targets">
                            <input type="hidden" name="target_codes" id="target_codes">
                            <input type="hidden" name="department_id" id="department_id_batch"
                                value="{{ $departmentCreds->id }}">
                            <div class="my-2">
                                <select name="month" id="month"
                                    class="w-60 appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 border-gray-300 focus:outline-indigo-600 sm:text-sm">
                                    <option value="">Bulan</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit"
                                    class="py-1.5 px-2 bg-blue-600 my-2 rounded-md text-white">Approve</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://unpkg.com/pdfobject"></script>
<script type="text/javascript" src="{{ asset('js/tableToExcel.js') }}"></script>

<script>
    let pdfData = {};
    let currentIndexes = {};
    let modalOrder = {};

    document.querySelectorAll('.modal').forEach(modal => {
        const month = modal.dataset.month;
        const actualId = modal.id.split('-').pop();
        if (!modalOrder[month]) {
            modalOrder[month] = [];
        }
        modalOrder[month].push(actualId);
    });

    document.querySelectorAll('button[id^="open-modal-"]').forEach(button => {
        button.addEventListener('click', function() {
            const month = this.dataset.month;
            const actualId = this.dataset.actualId;
            fetchPdfUrls(month, actualId, this.id);
        });
    });

    document.querySelectorAll('button[id^="close-modal-"]').forEach(button => {
        button.addEventListener('click', function() {
            const actualId = this.id.split('-').pop();
            document.getElementById(`modal-${actualId}`).classList.add('hidden');
            document.getElementById(`modal-background-${actualId}`).classList.add('hidden');
        });
    });

    document.querySelectorAll('div[id^="modal-background-"]').forEach(background => {
        background.addEventListener('click', function() {
            const actualId = this.id.split('-').pop();
            document.getElementById(`modal-${actualId}`).classList.add('hidden');
            document.getElementById(`modal-background-${actualId}`).classList.add('hidden');
        });
    });

    function fetchPdfUrls(month, actualId, buttonId) {
        const filePreviewUrl = "{{ route('report.showFileDept') }}";
        const url = `${filePreviewUrl}?month=${month}&actual_id=${actualId}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const index = buttonId.split('-').pop();
                pdfData[index] = data;
                currentIndexes[index] = 0;
                updatePdfViewer(buttonId, actualId);

                document.getElementById(`modal-${actualId}`).classList.remove('hidden');
                document.getElementById(`modal-background-${actualId}`).classList.remove('hidden');
            })
            .catch(error => console.error('Error fetching PDF URLs:', error));
    }

    function updatePdfViewer(buttonId, actualId) {
        const index = buttonId.split('-').pop();
        const pdfObject = document.getElementById(`pdfObject-${index}`);
        const prevButton = document.getElementById(`prevButton-${index}`);
        const nextButton = document.getElementById(`nextButton-${index}`);
        const fileNumberElement = document.getElementById(`fileNumber-modal-${index}`);
        const commentElement = document.getElementById(`comment-modal-${index}`);
        const targetModalId = document.getElementById(`t-modal-${actualId}`);
        const actualModalId = document.getElementById(`a-modal-${actualId}`);
        const percentModalId = document.getElementById(`percent-modal-${actualId}`);

        const currentIndex = currentIndexes[index];
        const pdfUrls = pdfData[index];

        if (pdfUrls.length > 0) {
            const currentPdf = pdfUrls[currentIndex];
            const baseUrl = "{{ asset('record_files') }}";
            pdfObject.data = `${baseUrl}/${currentPdf.record_file}`;
            fileNumberElement.textContent = `${currentPdf.kpi_code} | ${currentPdf.kpi_item}`;

            if (currentPdf.comment != null) {
                commentElement.textContent = `${currentPdf.comment}`;
            }

            if (currentPdf.target != null) {
                targetModalId.textContent = parseFloat(currentPdf.target).toLocaleString('en-US');
            } else {
                targetModalId.textContent = '';
            }

            if (currentPdf.actual != null) {
                actualModalId.textContent = parseFloat(currentPdf.actual).toLocaleString('en-US');
            } else {
                actualModalId.textContent = '';
            }

            if (currentPdf.kpi_percentage != null) {
                percentModalId.textContent = `${currentPdf.kpi_percentage}`;
            } else {
                percentModalId.textContent = '';
            }
        } else {
            pdfObject.data = '';
            fileNumberElement.textContent = '';
            commentElement.textContent = '';
        }

        prevButton.replaceWith(prevButton.cloneNode(true));
        nextButton.replaceWith(nextButton.cloneNode(true));

        const newPrevButton = document.getElementById(`prevButton-${index}`);
        const newNextButton = document.getElementById(`nextButton-${index}`);

        newPrevButton.addEventListener('click', function() {
            const modalElement = document.getElementById(`modal-${actualId}`);
            const month = modalElement.dataset.month;
            const modalIds = modalOrder[month];
            if (modalIds) {
                const currentModalIndex = modalIds.indexOf(actualId);
                if (currentModalIndex > 0) {
                    const prevModalId = modalIds[currentModalIndex - 1];
                    const prevActualId = prevModalId.split('-').pop();
                    document.getElementById(`modal-${actualId}`).classList.add('hidden');
                    document.getElementById(`modal-background-${actualId}`).classList.add('hidden');
                    fetchPdfUrls(month, prevActualId, `prevButton-${prevActualId}`);
                }
            }
        });

        newNextButton.addEventListener('click', function() {
            const modalElement = document.getElementById(`modal-${actualId}`);
            const month = modalElement.dataset.month;
            const modalIds = modalOrder[month];
            if (modalIds) {
                const currentModalIndex = modalIds.indexOf(actualId);
                if (currentModalIndex < modalIds.length - 1) {
                    const nextModalId = modalIds[currentModalIndex + 1];
                    const nextActualId = nextModalId.split('-').pop();
                    document.getElementById(`modal-${actualId}`).classList.add('hidden');
                    document.getElementById(`modal-background-${actualId}`).classList.add('hidden');
                    fetchPdfUrls(month, nextActualId, `nextButton-${nextActualId}`);
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-checkbox').forEach(function(checkbox) {
            if (checkbox.checked) {
                checkbox.disabled = true;
            }
            checkbox.addEventListener('change', function() {
                const actualId = this.getAttribute('data-actual-id');
                const status = this.getAttribute('data-status');
                const isChecked = this.checked;
                const url = "{{ route('actual.updateActualDept') }}"

                fetch(url, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            actual_id: actualId,
                            status: status,
                            checked: isChecked
                        })
                    })
                    .then(response => response.json())
                    .then(data => console.log('Success:', data))
                    .catch((error) => console.error('Error:', error));
            });
        });
    });

    // Export Table Setup
    let exportButton = document.getElementById("exportBtn");
    exportButton.addEventListener("click", e => {
        let originalTable = document.querySelector("#exportTable");
        let tableCopy = originalTable.cloneNode(true);
        let departmentName = document.getElementById("departmentName").textContent.replace('Divisi: ', '')
        .trim();

        tableCopy.querySelectorAll('.exclude-from-export').forEach(element => {
            element.remove();
        });

        const totalCells = tableCopy.querySelectorAll('.changeColSpan');
        totalCells.forEach(cell => {
            cell.setAttribute('colspan', '5');
        });

        tableCopy.querySelectorAll('td').forEach(cell => {
            let text = cell.textContent.trim();
            if (text === 'No') cell.setAttribute('data-f-color', 'FFFF0000');
            else if (text === 'Yes') cell.setAttribute('data-f-color', 'FF00CC00');
            else if (text === 'Review') cell.setAttribute('data-f-color', 'FF3399FF');
            else if (text === 'Check 2') cell.setAttribute('data-f-color', 'FFFF9900');
        });

        TableToExcel.convert(tableCopy, {
            name: `Dept_${departmentName}_Report.xlsx`,
            sheet: {
                name: "Sheet 1"
            }
        });
    });

    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.selected-item');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    document.getElementById('open-batch-modal').addEventListener('click', function() {
        const selectedItems = [];
        const targetCodes = [];
        document.querySelectorAll('.selected-item:checked').forEach(checkbox => {
            selectedItems.push(checkbox.value);
            targetCodes.push(checkbox.getAttribute('data-code'));
        });

        const selectedTargetsInput = document.getElementById('selected_targets');
        const targetCodesInput = document.getElementById('target_codes');

        if (selectedTargetsInput && targetCodesInput) {
            selectedTargetsInput.value = selectedItems.join(',');
            targetCodesInput.value = targetCodes.join(',');
        }

        document.getElementById('batch-modal').classList.remove('hidden');
        document.getElementById('batch-modal-bg').classList.remove('hidden');
    });

    document.getElementById('close-batch-modal').addEventListener('click', function() {
        document.getElementById('batch-modal').classList.add('hidden');
        document.getElementById('batch-modal-bg').classList.add('hidden');
    });

    document.getElementById('batch-approve-form').addEventListener('submit', function(event) {
        const selectedItems = [];
        const targetCodes = [];
        document.querySelectorAll('.selected-item:checked').forEach(checkbox => {
            selectedItems.push(checkbox.value);
            targetCodes.push(checkbox.getAttribute('data-code'));
        });

        const selectedTargetsInput = document.getElementById('selected_targets');
        const targetCodesInput = document.getElementById('target_codes');

        if (selectedTargetsInput && targetCodesInput) {
            selectedTargetsInput.value = selectedItems.join(',');
            targetCodesInput.value = targetCodes.join(',');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="checkbox"][id^="is_invalid_"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const actualId = this.id.replace('is_invalid_', '');
                const button = document.getElementById('set-invalid-btn-' + actualId);
                const invalidComment = document.getElementById('invalid-comment-' + actualId);
                const sendEmailForm = document.getElementById('send-email-form-' + actualId);

                if (button) {
                    button.classList.toggle('hidden', !this.checked);
                    invalidComment.classList.toggle('hidden', !this.checked);
                    sendEmailForm.classList.toggle('hidden', this.checked);
                }
            });
        });
    });
</script>
