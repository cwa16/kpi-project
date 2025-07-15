<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-gray-100 border border-gray-200 shadow-md shadow-black/10 rounded-md">
        @php 
        $yearQuery = request()->query('year');
        $currentYear = Carbon\Carbon::now()->year;
        $startYear = 2024; 
        $endYear = $currentYear + 2;
        $department_id = auth()->user()->department_id;
        $role = auth()->user()->role;
        $currentMonth = Carbon\Carbon::now()->month;
        $monthQuery = request()->query('month');
        $date = DateTime::createFromFormat('!m', $monthQuery);
        // dd($date);
        $monthName = $date->format('F');
        function formatDate($dateString) {
        if ($dateString) {
            return Carbon\Carbon::parse($dateString)->format('d M Y H:i:s');
        }
        return '';
    }  
        
        @endphp
        <div class="flex justify-between">
            <div class="p-0">
                <div class="px-1">
                    <span class="font-medium text-gray-600 text-sm">PT BRIDGESTONE KALIMANTAN PLANTATION</span>
                </div>
                <div class="px-1">
                    <span class=" font-bold text-gray-600 text-2xl">{{ $titlePage }}</span>
                </div>
                <div class="px-1">
                    <span class=" font-semibold text-gray-600 text-base">Periode {{ $monthName }} {{ $yearQuery }}</span>
                </div>
            </div>
            <div class="p-0 5">
                <form action="{{ url('logs/log-input') }}" method="GET">
                 <div class="flex gap-x-2">
                     <div class="my-2">
                         <select name="month" id="month" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                             <option value="{{ $currentMonth }}">-- Bulan --</option>
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
                     <div class="my-2">
                         <select name="year" id="year" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                             <option value="{{ $currentYear }}">-- Tahun --</option>
                             @for ($year = $startYear; $year <= $endYear; $year++)
                             <option value="{{ $year }}">{{ $year }}</option>
                             @endfor
                         </select>
                     </div>
                     @if (auth()->user()->role != 'Inputer')

                     <div class="mt-2 mb-1 mx-2">
                         <select name="department" id="department" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            <option value="">-- Departmen --</option>
                            @if ($role == 'Approver' || $role == 'Mng Approver')
                            <option value="All Dept">All Dept</option>
                            @endif
                            @foreach ($allDept as $item)  
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                     <div class="my-2">
                         <button type="submit" class="rounded-md bg-blue-500 text-white p-2">Filter</button>
                     </div>
                     {{-- <input type="hidden" name="department_id" id="" value="{{ $department_id }}"> --}}
                 </div>
                 </form>
            </div>
        </div>
        
        <div class="flex justify-end">
            @php
            // dd($targetUnitCountAll);
            $monthQuery = request()->query('month');
            $departmentQuery = request()->query('department');
            $totalTgUnit = 0;
            $totalTgUnitDept = 0;
            $totalsTg = 0;
                switch ($monthQuery) {
                    case '01':
                    if ($departmentQuery == 'All Dept') {
                            $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                return $item->department_id == $department_id && $item->total_1 ?? 0;
                            }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                return $item->department_id == $department_id && $item->total_1 ?? 0;
                            }) ?? 0;
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_1 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_1 ?? 0;
                        }

                        $totalsTg = $totalTgUnitAll['total_1'] ?? 0;
                        break;

                    case '02':

                    if ($departmentQuery == 'All Dept') {   
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_2 ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_2 ?? 0;
                                }) ?? 0;
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_2 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_2 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_2'] ?? 0;
                        break;

                    case '03':

                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                            return $item->department_id == $department_id && $item->total_3 ?? 0;
                                }) ?? 0;
                        $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                            return $item->department_id == $department_id && $item->total_3 ?? 0;
                            }) ?? 0;
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_3 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_3 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_3'] ?? 0;
                        break;

                    case '04':
                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_ ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_4 ?? 0;
                                }) ?? 0;
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_4 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_4 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_4'] ?? 0;
                        break;

                    case '05':

                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_5 ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_5 ?? 0;
                                }) ?? 0;
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_5 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_5 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_5'] ?? 0;
                        break;
                        
                    case '06':
                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_6 ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_6 ?? 0;
                                }) ?? 0;
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_6 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_6 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_6'] ?? 0;
                        break;

                    case '07':

                    if ($departmentQuery == 'All Dept') {
                        
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_7 ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_7 ?? 0;
                                }) ?? 0;
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_7 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_7 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_7'] ?? 0;
                        break; 

                    case '08':
                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_8 ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_8 ?? 0;
                                }) ?? 0;
                           
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_8 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_8 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_8'] ?? 0;
                        break;

                    case '09':
                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_9 ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_9 ?? 0;
                                }) ?? 0;
                                
                            } else {
                                $totalTgUnit = $targetUnitCountAll->total_9 ?? 0;
                                $totalTgUnitDept = $targetUnitCountAllDept->total_9 ?? 0;
                            }
                            $totalsTg = $totalTgUnitAll['total_9'] ?? 0;
                        break;

                    case '10':

                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                            return $item->department_id == $department_id && $item->total_10 ?? 0;
                                }) ?? 0;
                        $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                            return $item->department_id == $department_id && $item->total_10 ?? 0;
                                }) ?? 0;     
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_10 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_10 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_10'] ?? 0;
                        break;

                    case '11':
                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                            return $item->department_id == $department_id && $item->total_11 ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                            return $item->department_id == $department_id && $item->total_11 ?? 0;
                                }) ?? 0;
                    } else {
                            $totalTgUnit = $targetUnitCountAll->total_11 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_11 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_11'] ?? 0;
                        break;
                    case '12':
                    if ($departmentQuery == 'All Dept') {
                        $totalTgUnit = $targetUnitCountAll->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_12 ?? 0;
                                }) ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id && $item->total_12 ?? 0;
                                }) ?? 0;
                           
                        } else {
                            $totalTgUnit = $targetUnitCountAll->total_12 ?? 0;
                            $totalTgUnitDept = $targetUnitCountAllDept->total_12 ?? 0;
                        }
                        $totalsTg = $totalTgUnitAll['total_12'] ?? 0;
                        break;
                    default:
                        $totalTgUnit = 0;
                        $totalTgUnitDept = 0;
                        $totalsTg = 0;
                        break;
                }

                if ($departmentQuery == 'All Dept') {
                    $totalFl = $actualFilledCount->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id;
                                }) ?? 0;
                    $totalFlDept = $actualFilledCount->first(function($item) use ($department_id) {
                                    return $item->department_id == $department_id;
                                }) ?? 0;
                    
                } else { 
                $totalFl = $actualFilledCount->total_filled ?? 0;
                $totalFlDept = $actualFilledCountDept->total_filled ?? 0;

                }
                
                
                $totalCheck = $actualCheckedCount ?? 0;
                $totalCheckDept = $actualCheckedCountDept ?? 0;
                $totalFlAll = $totalFl + $totalFlDept;
                $totalCheckedAll = $totalCheck + $totalCheckDept;
                $month = request()->query('month');
                $year = request()->query('year');
                $totalTgAll = $totalTgUnit + $totalTgUnitDept;
                // dd($totalTgAll, $totalsTg)
                // dd($totalTgUnit, $totalTgUnitDept, $totalsTg);
                // dd($totalsTg, $totalCheck, $totalCheckDept);
                // dd($totalCheck, $totalCheckDept, $totalTgAll);

                // dd($totalCheckedAll, $totalTgAll)

                // dd($totalFlAll, $totalTgAll, $totalFl, $totalFlDept);
                
                // dd('total filled:', $totalFl, 'total filled dept:', $totalFlDept, 'total filled dept and indiv', $totalFlAll, 'total target Unit dept + indiv', $totalTgAll);
                // dd($totalFlAll, $totalTgAll);
            @endphp
            <div class="flex justify-end">
                <div class="p-0.5">
                    @if ($role == 'Inputer')
                    <form action="{{ url('/generate-pdf-input') }}" method="GET">
                        @php
                            $lastInput = $actualFilled->first(function($item) use ($department_id) {
                                return $item->department_id == $department_id;
                            });
    
                            
                        @endphp
                        <input type="hidden" name="department_id" id="department_id" value="{{ $department_id }}">
                        <input type="hidden" name="input_at" id="input_at" value="{{ $lastInput->input_at ?? '' }}">
                        <input type="hidden" name="input_by" id="input_by" value="{{ $lastInput->input_by ?? '' }}">
                        <input type="hidden" name="inputThisMonth" id="inputThisMonth" value="{{ $totalTgAll }}">
                        <input type="hidden" name="inputed" id="inputed" value="{{ $totalFlAll }}">
                        <button type="submit" class="rounded-md bg-green-700 text-white p-2">Generate TTE</button>
                    </form>
                    @endif
                </div>
                <div class="p-0.5">
                    @if ($role == 'Checker Div 1' || $role == 'Checker Div 2' || $role == 'Checker WS' || $role == 'Checker Factory')
                    <form action="{{ url('/generate-pdf-check') }}" method="GET">
                        @php
                            $lastInput = $actualFilled->first(function($item) use ($department_id) {
                                return $item->department_id == $department_id;
                            });
                        @endphp
                        <input type="hidden" name="department_id" id="department_id" value="{{ $department_id }}">
                        <input type="hidden" name="input_at" id="input_at" value="{{ $lastInput->checked_at ?? '' }}">
                        <input type="hidden" name="input_by" id="input_by" value="{{ $lastInput->checked_by ?? '' }}">
                        <input type="hidden" name="checkedThisMonth" id="checkedThisMonth" value="{{ $totalsTg }}">
                        <input type="hidden" name="checked" id="checked" value="{{ $totalCheckedAll }}">
                        <button type="submit" class="rounded-md bg-blue-500 text-white p-2">Generate TTE</button>
                    </form>
                    @endif
                </div>
            </div>  
        </div>
        @if ($role == 'Checker Div 1' || $role == 'Checker Div 2' || $role == 'Checker WS' || $role == 'Checker Factory')
        <div class="flex gap-x-3">
            <div class="">
                <button type="button" onclick="openModal('checkModal')" class="hover:text-blue-500 rounded-md">
                    <p>Not Checked: {{ $totalsTg - $totalCheckedAll }}</p>
                </button>

            </div>
            <div class="">
                <p>Checked: {{ $totalCheckedAll }}</p>
            </div>
            <div class="">
                <p>Total this month: {{ $totalsTg }}</p>
            </div>
        </div>
        @elseif ($role == 'Inputer')
        <div class="flex gap-x-3">
            <div class="">
                <p>Not Inputed: {{ $totalTgAll - $totalFlAll }}</p>
            </div>
            <div class="">
                <p>Inputed: {{ $totalFlAll }}</p>
            </div>
            <div class="">
                <p>Total this month: {{ $totalTgAll }}</p>
            </div>
        </div>
        @endif
        <table class="w-full bg-white">
            <tr>
                <th style="width: 7%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-0 px-4 bg-blue-700">Dept</th>
                <th style="width: 3%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-0 px-4 bg-blue-700">Total Employee</th>
                <th style="width: 10%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-0 px-4 bg-blue-700">Input</th>
                <th style="width: 10%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-0 px-4 bg-blue-700">Checked</th>
                <th style="width: 10%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-0 px-4 bg-blue-700">Approved</th>
            </tr>
            @php
            $i = 0;
            
        @endphp
        @forelse ($allDept as $department)
        @php
            $i++;
        @endphp
        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}"> 
            <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-[13px]">{{ $department->name}}</td>
            @php
                $totalEmployee = $countEmployees->first(function($item) use ($department) {
                    return $item->department_id == $department->id;
                });
                $acc = $actualCheckedCheck->first(function($item) use ($department) {
                    return $item->department_id == $department->id;
                });
                $ac = $actualChecked->first(function($item) use ($department) {
                    return $item->department_id == $department->id;
                });
                $afc = $actualFilledCheck->first(function($item) use ($department) {
                    return $item->department_id == $department->id;
                });
                
                $ap = $actualApproved->first(function($item) use ($department) {
                    return $item->department_id == $department->id;
                });
                
                $af = $actualFilled->first(function($item) use ($department) {
                    return $item->department_id == $department->id;
                });
                
                // dd($af);
                // dump($totalFlAll, $totalTgAll, $totalFl, $totalFlDept, $af)
            @endphp
            <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-[13px] text-center">{{ $totalEmployee->total_employee ?? 0 }}</td>

            
            <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-[13px] text-center">
                {{ $af ? $af->input_by : '' }} | {{  $af ? formatDate($af->input_at) : '' }}
            </td>
            <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-[13px] text-center">
                {{ $afc ? '' : ($af->checked_by ?? '') }} | {{ $afc ? '' : (formatDate($af->checked_at ?? '')) }}
            </td>
            <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-[13px] text-center">
                {{ $af ? $af->approved_by : '' }} | {{  $af ? formatDate($af->approved_at) : '' }}
            </td>
            @empty
            <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-[13px] text-center" colspan="5">No data available</td>
        </tr>

        @endforelse
    </table>
</div>

<div id="checkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center flex hidden">
    <div class="bg-white rounded-lg p-4 w-[500px]">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">Not Checked List</h2>
            <button id="closeCheckModal" class="text-gray-500 hover:text-gray-700" onclick="closeModal('checkModal')">&times;</button>
        </div>
        <div class="mt-4">
            <p>KPI Employee</p>
            <table class="w-full">
                <tr>
                    <th style="width: 3%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                    <th style="width: 70%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Dept</th>
                    <th style="width: 27%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Detail Item</th>
                    <th style="width: 27%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Not Checked</th>
                </tr>
                @php
                $i = 0;
                @endphp
                @foreach ($actualCheckedCountGroup as $item)
                @php
                $i++;
                @endphp
                <tr>
                    <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-center">
                        {{ $i }}
                    </td>
                    <td class="border-2 border-gray-400 tracking-wide px-2 py-0">
                       {{ $item->department_name }}
                    </td>
                    <td class="border-2 border-gray-400 tracking-wide px-2 py-0">
                       {{ $item->kpi_code }}
                    </td>
                    <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-center">
                        {{ $item->total_not_checked }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="mt-4">
            <p>KPI Dept</p>
            <table class="w-full">
                <tr>
                    <th style="width: 3%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                    <th style="width: 70%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Dept</th>
                    <th style="width: 27%;" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Not Checked</th>
                </tr>
                @php
                $i = 0;
                @endphp
                @foreach ($actualCheckedCountDeptGroup as $item)
                @php
                $i++;
                @endphp
                <tr>
                    <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-center">
                        {{ $i }}
                    </td>
                    <td class="border-2 border-gray-400 tracking-wide px-2 py-0">
                       {{ $item->department_name }}
                    </td>
                    <td class="border-2 border-gray-400 tracking-wide px-2 py-0 text-center">
                        {{ $item->total_not_checked }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.3.0/pdfobject.min.js" integrity="sha512-Nr6NV16pWOefJbWJiT8SrmZwOomToo/84CNd0MN6DxhP5yk8UAoPUjNuBj9KyRYVpESUb14RTef7FKxLVA4WGQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

</script>
