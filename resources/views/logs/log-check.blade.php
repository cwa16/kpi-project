<x-app-layout :title="$title" :desc="$desc">
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
                <span class=" font-bold text-gray-600 text-2xl">Log Pengecekan KPI</span>
            </div>
        </div>
        <form action="{{ route('log-check.index') }}">
        <div class="p-0 flex gap-2">
            <div class="my-2">
                <select name="semester" id="semester" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    <option value="">-- Semester --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            <div class="my-2">
                <select name="year" id="year" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    <option value="{{ $currentYear }}">-- Tahun --</option>
                    @for ($year = $startYear; $year <= $endYear; $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="my-2">
                <button type="submit" class="rounded-md bg-blue-500 text-white p-2">Filter</button>
            </div>
        </div>
    </form>
    </div>

        <table class="w-full bg-white table-fixed">
            <tr>
                
                <th style="width: 1%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700" rowspan="2">No</th>
                <th style="width: 3.5%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700" rowspan="2">Dept</th>
                <th style="width: 3%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700" rowspan="2">KPI Dept</th>
                <th style="width: 3%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700" rowspan="2">KPI Individu</th>
                <th style="width: 3%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700" rowspan="2">Total</th>

                @foreach ($months as $month)
                    <th style="width: 5%" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700" colspan="3">
                        {{ \Carbon\Carbon::create()->month($month)->format('M') }}
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($months as $month)
                    <th style="width: 4%" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700">OK</th>
                    <th style="width: 4%" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700">Not OK</th>
                    <th style="width: 4%" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700">Total</th>
                @endforeach
            </tr>
            @foreach ($departments as $department) 
            @php
                $i++
            @endphp        
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ $i }}</td>
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">{{ $department->name }}</td>
                @php
                $targetCountDept = $targetCountsDept->first(function($item) use ($department) {
                    return $item->code == $department->code;
                });
                @endphp
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ $targetCountDept->total ?? ''}}</td>
                @php
                    $targetCount = $targetCounts->first(function($item) use ($department) {
                        return $item->code == $department->code;
                    });
                    $totalTarget = ($targetCount->total ?? 0) + ($targetCountDept->total ?? 0);
                @endphp
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ $targetCount->total ?? '' }}</td>
                @if ($totalTarget > 0)
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ $totalTarget }}</td>
                @else
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @foreach ($months as $month)
                @php

                    if ($semesterQuery == '1') {
                        $targetUnitCounts = $targetUnitCounts1;
                        $targetUnitCountsDept = $targetUnitCountsDept1;
                    } else {
                        $targetUnitCounts = $targetUnitCounts2;
                        $targetUnitCountsDept = $targetUnitCountsDept2;
                    }

                    $targetColumn = 'total_' . $month;
                    $targetUnitCount = $targetUnitCounts->first(function($item) use ($department) {
                        return $item->department_id == $department->id;
                    });
                    $targetUnitCountDept = $targetUnitCountsDept->first(function($item) use ($department) {
                        return $item->department_id == $department->id;
                    });

                    $totalTargetUnitCount = ($targetUnitCount->$targetColumn ?? 0) + ($targetUnitCountDept->$targetColumn ?? 0);

                    $actual = $actualCounts->first(function($item) use ($department, $month) {
                        return $item->month == $month && $item->department_code == $department->code;
                    });
                    $actualDept = $actualCountsDept->first(function($item) use ($department, $month) {
                        return $item->month == $month && $item->department_code == $department->code;
                    });
                    
                    $totalActual = ($actual->total ?? 0) + ($actualDept->total ?? 0);
                    // dump($month, $actual->month ?? 'none');
                    // dump($department->code, $month,$actual->month ?? 'none')


                    // dump('Count iteration ' . $targetColumn .':', $targetUnitCountDept, 'Count Employee iteration ' . $targetColumn .':', $targetUnitCount)
                    
                @endphp
                
                @if ($totalActual > 0)
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                {{ $totalActual }}
                </td>
                @else
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                    @if ($totalTargetUnitCount > 0)
                    {{ $totalTargetUnitCount - $totalActual }}
                    @else
                    <span></span>
                    @endif
                </td>
                <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                    @if ($totalTargetUnitCount > 0)
                    {{ $totalTargetUnitCount }}
                    @else
                    <span></span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </table>
        {{-- @php
            dd($targetColumn, $targetUnitCount, $targetUnitCountDept, $totalTargetUnitCount, $month, $department->id, 'TU Count', $targetUnitCounts, 'TU Dept Count', $targetUnitCountsDept);
        @endphp --}}
    </div>
</x-app-layout>