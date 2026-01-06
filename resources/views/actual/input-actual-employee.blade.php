<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-gray-100 border border-gray-100 shadow-md shadow-black/10 rounded-md">
        <div class="">
            <span class="font-bold text-2xl">Monitoring Input Pencapaian KPI</span>
        </div>
        <div class="">
            <span class="font-semibold text-base mb-2">Periode Semester {{ request()->query('semester') }} Tahun {{ request()->query('year') }}</span>
        </div>
        <div class="p-0 mt-2">
        <table class="w-full bg-white">
            @php
            $currentSemester = request()->query('semester');
            $months = [];

            if ($currentSemester == 1) {
                $months = [
                    '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr',
                    '5' => 'May', '6' => 'Jun'
                ];
            } else {
                $months = [
                    '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct',
                    '11' => 'Nov', '12' => 'Dec'
                ];
            }
            @endphp
            <tr>
                <th style="width: 3%;" class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-1 bg-blue-700">No.</th>
                <th style="width: 4%" class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-1 bg-blue-700">Kode KPI</th>
                <th class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-1 bg-blue-700">KPI</th>
                <th class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-1 bg-blue-700">Periode</th>
                @foreach ($months as $monthName)
                    <th class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-1 bg-blue-700">{{ $monthName }}</th>
                @endforeach
                <th class="border border-gray-400 text-[13px] tracking-wide font-medium text-white py-1 bg-blue-700">Aksi</th>
            </tr>
            @php
                $i = 0;
            @endphp
            @forelse ($targets as $target)
                @php
                    $i++;
                @endphp
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                    <td class="border border-gray-400 text-[10px] tracking-wide  py-0 px-2 text-center">{{ $i }}</td>
                    <td class="border border-gray-400 text-[10px] tracking-wide  py-0 px-2">{{ $target->code }}</td>
                    <td class="border border-gray-400 text-[10px] tracking-wide  py-0 px-2">{{ $target->indicator }}</td>
                    <td class="border border-gray-400 text-[10px] tracking-wide  py-0 px-2">{{ $target->period }}</td>
                    @php

                    if ($currentSemester == 1) {
                        $targetUnits = $targetUnits1;
                        $targetRange = range(1, 6);
                    } else {
                        $targetUnits = $targetUnits2;
                        $targetRange = range(7, 12);
                    }
                    @endphp
                    @foreach ($months as $month => $monthName)
                    @php
                        $actual = $actuals->first(function($item) use ($target, $month) {

                        $itemMonth = \Carbon\Carbon::parse($item->actual_date)->format('m');
                        $itemIndicator = $item->kpi_item;
                        $targetIndicator = $target->indicator;

                        // Debugging output
                        // dump('Item Month:', $itemMonth, 'Month:', $month, 'Item Indicator:', $itemIndicator, 'Target Indicator:', $targetIndicator);

                        return $itemMonth == $month && $itemIndicator == $targetIndicator;
                    });
                    $targetUnitCheck = $targetUnits->first(function($item) use ($target, $month){
                        return $target->id == $item->target_id;
                    });

                    $targetColumn = 'target_' . $month;

                    @endphp

                    @if ($actual !== null && $targetUnitCheck->$targetColumn !== null && $actual->status == 'Revise')
                    <td style="width: 6%" class="border border-gray-400 text-[10px] tracking-wide py-0 px-2 text-center">
                        <i class="ri-error-warning-fill text-xl text-yellow-500"></i>
                    </td>
                    @elseif ($actual !== null && $targetUnitCheck->$targetColumn !== null)
                        <td style="width: 6%" class="border border-gray-400 text-[10px] tracking-wide py-0 px-2 text-center">
                            <i class="ri-checkbox-circle-fill text-xl text-green-500"></i>
                        </td>
                    @elseif ($targetUnitCheck->$targetColumn !== null)
                    <td style="width: 6%" class="border border-gray-400 text-[10px] tracking-wide py-0 px-2 text-center">
                        <i class="ri-pencil-fill text-xl text-gray-500"></i>
                    </td>
                    @else
                        <td style="width: 6%" class="border border-gray-400 text-[10px] tracking-wide py-0 px-2 text-center">

                        </td>
                    @endif
                    @endforeach
                    <td class="border border-gray-400 text-[10px] tracking-wide  py-0 px-2 text-center">
                        <div class="flex justify-center gap-2">
                            @php
                                $year = request()->query('year');
                            @endphp
                            <a href="{{ route('actual.edit', $target->id . '?year=' . $year ) }}">
                                <i class="ri-edit-box-line p-0.5 text-xl bg-yellow-400 text-white rounded-sm"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="border border-gray-400 tracking-wide  py-0 px-2 text-center">Data Tidak ditemukan</td>
                </tr>
            @endforelse
        </table>
    </div>
    </div>
</x-app-layout>
