<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        @php
            $currentYear = Carbon\Carbon::now()->year;
            $startYear = 2024; 
            $endYear = $currentYear + 2;
            $range = range(1, 12);
            $year = request()->query('year') ?? $currentYear;
            
        @endphp
        <div class="flex justify-between">
            <div class="p-0">
                <span class="font-bold text-2xl">PT. BRIDGESTONE KALIMANTAN PLANTATION</span>
            </div>
        </div>
        <div class="flex justify-center mt-0.5">
            <div class="p-1">
                <span class="text-gray-600 font-bold text-xl text-center">Summary - KPI Report {{ 'Dept' }}</span>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="flex items-start">
                <form action="{{ route('report.departmentTargetReport') }}" method="GET">
               
                        <select name="item" id="item" class="rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            @if (request()->query('item'))
                            <option value="{{ request()->query('item') }}">{{ request()->query('item') }}</option>
                            @else
                            <option value="">-- Item KPI --</option>
                            @endif
                            @foreach ($indicatorList as $item)
                            <option value="{{ $item->indicator }}">{{ \Illuminate\Support\Str::limit($item->indicator, 25) }}</option>    
                            @endforeach
                        </select>
                        <input type="hidden" name="year" id="year" value="{{ $year }}">
                    <button class="py-1.5 px-2 bg-blue-600 my-2 rounded-md text-white">
                        Filter
                    </button>

                </form>
            </div>
            <div class="flex justify-end">
                <button id="exportBtn" class="p-1.5 rounded-md text-white bg-green-500">Export</button>
            </div>
        </div>

        
        <table class="w-full table-fixed mt-2" id="exportTable">
            <tr>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 4%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 20%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Dept</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 5%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Trend</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 6%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Periode Review</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 5%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Unit</th>
                @foreach ($range as $month)
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" style="width: 5%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">{{ DateTime::createFromFormat('!m', $month)->format('M') }}</th>
                @endforeach
            </tr>
            @php
                $i = 0;
            @endphp
            @forelse ($targets as $target)
            @php
            $i++
            @endphp
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-200'}}" >

                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" style="width: 3%" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center">{{ $i }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0">{{ $target->department }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" style="width: 28%" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center">{{ $target->trend }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center">{{ $target->period }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center">{{ $target->unit }}</td>
                @foreach ($range as $month)
                @php
                // dd($range);
                    $targetUnitField = 'target_' . $month;
                    $actual = $actuals->first(function($item) use ($month, $target){
                        return \Carbon\Carbon::parse($item->date)->format('m') == $month && $item->department == $target->department;
                    })
                @endphp 
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center">
                @if ($target->$targetUnitField !== null)
                    {{ $actual->actual ?? '' }}
                @else
                    <span>N/A</span>
                @endif
                </td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" colspan="24" class="border-2 border-gray-400 tracking-wide  py-0 px-2 text-center">Data Tidak ditemukan</td>
            </tr>
            @endforelse
            @php
                $monthlySums = [];
                foreach ($range as $month) {
                    $monthlySums[$month] = $actuals->filter(function($item) use ($month) {
                        return \Carbon\Carbon::parse($item->date)->format('m') == $month;
                    })->sum('actual');
                }
                // dd($monthlySums);
            @endphp
            <tr class="bg-[#FFE893] font-semibold">
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="#FFFFE893" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center"></td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="#FFFFE893" colspan="4" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center">Total</td>
                @foreach ($range as $month)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="#FFFFE893" class="border-2 border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center">
                    {{ $monthlySums[$month] ?? 'N/A' }}
                </td>
                @endforeach
            </tr>
        </table>
    </div>
</x-app-layout>

<script type="text/javascript" src="{{ asset('js/tableToExcel.js') }}"></script>
<script>
    let button = document.getElementById("exportBtn");

    button.addEventListener("click", e => {
    let table = document.querySelector("#exportTable");
    TableToExcel.convert(table, {
            name: "kpi-department-report.xlsx",
            sheet: {
                name: "Sheet 1"
            }
        });
    });
</script>