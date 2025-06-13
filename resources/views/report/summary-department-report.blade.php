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
                        Summary KPI Department Report
                    </span>
                </div>
                <div class="pl-1">
                    <span class="text-gray-600">Periode: {{ request()->query('year') }}</span>
                </div>
            </div>
            <div class="flex justify-end">
                <form action="{{ route('report.summaryDept') }}" method="GET">
                <div class="p-0 flex justify-between gap-x-1">
                    <div class="relative mt-1 rounded-md">
                        <div class="mt-2">
                            <select name="year" id="year" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="{{ request()->query('year') }}">-- Tahun --</option>
                                @for ($year = $startYear; $year <= $endYear; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center">
                        </div>
                    </div>
                    <div class="relative mt-1 rounded-md">
                        <div class="mt-2 mb-0">
                            <select name="department[]" multiple id="department" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="">-- Department --</option>
                                @foreach ($allDept as $dept)
                                <option value="{{ $dept->id }}">
                                    {{ collect(request()->input('department'))->contains($dept->id) ? 'selected' : '' }}
                                    {{ $dept->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center">
                        </div>
                      </div>
                    <div class="relative mt-1 rounded-md">
                        <div class="mt-2 mb-1">
                            <select name="status[]" multiple id="status" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="">-- Status --</option>
                                @foreach ($allOccupation as $item)  
                                <option value="{{ $item->status }}">
                                    {{ collect(request()->input('status'))->contains($item->status) ? 'selected' : '' }}
                                    {{ $item->status }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center">
                        </div>
                      </div>
                      <div class="my-2">
                        <button type="submit" class="rounded-md bg-blue-500 text-white p-2">Filter</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        @if (request()->query('department') && request()->query('status') == '')
        <div class="flex justify-end">
            <button id="exportBtn" class="p-1.5 rounded-md text-white bg-green-500 mb-1">Export</button>
        </div>
        @endif
       <div class="p-0">
        <table id="exportTable" class="w-full bg-white table-fixed">
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 3%">No.</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 10%">Dept</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 6%">NIK</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 23%">Nama</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 12%">Posisi</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" colspan="3">Pencapaian KPI Dept 30%</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" colspan="3">Pencapaian KPI Individu 70%</th>
                <th style="width: 6%" data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2">Total Rata Rata</th>
              <tr>
               <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Semester 1</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Semester 2</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Rata Rata</td>
               <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Semester 1</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Semester 2</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Rata Rata</td>
              </tr>

              @php
                  $i = 0;
                  $startIndex = ($employees->currentPage() - 1) * $employees->perPage() + 1;
                  $totalSemester1Weight = 0;
                    $totalSemester2Weight = 0;
                    $totalSemester1WeightSum = 0;
                    $totalSemester2WeightSum = 0;
                    $totalSemester1DeptWeight = 0;
                    $totalSemester2DeptWeight = 0;
                    $totalWeightSum = 0;
                    $totalDeptWeightSum = 0;
                    $totalAverage = 0;
                    $totalAverageSum = 0;
                    $rowCount = 0;
              @endphp
              @foreach ($employees as $index => $employee)
              @php
                  $i++;
                //   dd($employee, $sumGroupSemester1);
                $employeeId = $employee->employee_id;
                $departmentId = $employee->department_id;

                $sumSemester1 = $sumGroupSemester1[$employeeId] ?? 0;
                $sumSemester2 = $sumGroupSemester2[$employeeId] ?? 0;
                $totalSumEmployee = $totalSumSemester[$employeeId] ?? 0;
                $sumSemester1Dept = $sumGroupSemester1Dept[$departmentId] ?? 0;
                $sumSemester2Dept = $sumGroupSemester2Dept[$departmentId] ?? 0;
                $totalSumDept = $totalSumSemesterDept[$departmentId] ?? 0;
                $totalAll = ($totalSumEmployee ?? 0) + ($totalSumDept ?? 0);

                $totalSemester1Weight = PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($sumSemester1);
                $totalSemester2Weight =  PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($sumSemester2);
                $totalSemester1DeptWeight =  PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($sumSemester1Dept);
                $totalSemester2DeptWeight =  PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($sumSemester2Dept);
                $totalWeightSum =  PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($totalSumEmployee);
                $totalDeptWeightSum = PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($totalSumDept);
                $totalAverage = PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($totalAll);

                $totalSemester1WeightSum += $totalSemester1Weight;
                $totalSemester2WeightSum += $totalSemester2Weight;
                $totalAverageSum += $totalAverage;
                $rowCount++;
                  
              @endphp
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ $startIndex + $index }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">{{ $employee->dept }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2" >{{ $employee->nik }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">     
                    {{ $employee->name }}
                </td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2" >{{ $employee->occupation }}</td>
                @if ($sumSemester1Dept > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                    <a href="{{ route('report.department', $employee->department_id) }}?year={{ $yearQuery }}&semester=1" class="hover:underline hover:text-blue-600">
                        {{ number_format($sumSemester1Dept, 1) }}%
                    </a>
                </td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @if ($sumSemester2Dept > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                    <a href="{{ route('report.department', $employee->department_id) }}?year={{ $yearQuery }}&semester=2" class="hover:underline hover:text-blue-600">
                    {{ number_format($sumSemester2Dept, 1) }}%
                    </a>
                </td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif
                
                @if ($totalSumDept > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalSumDept, 1) }}%</td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @if ($sumSemester1 > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                    <a href="{{ route('report.show', $employee->employee_id) }}?year={{ $yearQuery }}&semester=1" class="hover:underline hover:text-blue-600">
                    {{ number_format($sumSemester1, 1) }}%
                    </a>
                </td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @if ($sumSemester2 > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                    <a href="{{ route('report.show', $employee->employee_id) }}?year={{ $yearQuery }}&semester=1" class="hover:underline hover:text-blue-600">
                        {{ number_format($sumSemester2, 1) }}%
                    </a>
                </td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @if ($totalSumEmployee > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalSumEmployee, 1) }}%</td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalAll, 1) }}%</td>
              </tr>
              @endforeach

              @if(request()->query('department') && request()->query('status') == '')
              @php
              $finalTotalSemester1Weight = $rowCount > 0 ? $totalSemester1WeightSum / $rowCount : 0;
              $finalTotalSemester2Weight = $rowCount > 0 ? $totalSemester2WeightSum / $rowCount : 0;
              $finalTotalEmployeeWeight = $finalTotalSemester1Weight + $finalTotalSemester2Weight;
              $finalTotalAverage = $rowCount > 0 ? $totalAverageSum / $rowCount : 0;
              @endphp
              <tr class="bg-gray-200">
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center" colspan="4">Total</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalSemester1DeptWeight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalSemester2DeptWeight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalDeptWeightSum, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($finalTotalSemester1Weight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($finalTotalSemester2Weight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($finalTotalEmployeeWeight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($finalTotalAverage, 1) }}%</td>
            </tr>
            @endif
        </table>
    </div>

    @if (request()->query('department') && request()->query('status') == '')
    <div class="flex justify-between mt-2">
        <div class="mt-2">
            <span class="text-gray-600 p-1 text-2xl font-bold">
                KPI Sebelumnya
            </span>
        </div>
        @if (request()->query('department'))
        <div class="flex justify-end">
            <button id="exportBtn2" class="p-1.5 rounded-md text-white bg-green-500 my-2">Export</button>
        </div>
        @endif

    </div>

    <div class="mt-2">
        <table id="exportTable2" class="w-full">
            <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 3%">No.</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 10%">Dept</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 6%">NIK</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 23%">Nama</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2" style="width: 12%">Posisi</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" colspan="3">Pencapaian KPI Dept 30%</th>
                <th data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" colspan="3">Pencapaian KPI Individu 70%</th>
                <th style="width: 6%" data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700" rowspan="2">Total Rata Rata</th>
              <tr>
               <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Semester 1</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Semester 2</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Rata Rata</td>
               <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Semester 1</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Semester 2</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FF0066FF" data-f-color="FFFFFFFF" class="border-2 border-gray-400 text-[13px] tracking-wide font-medium text-white py-0.5 px-2 bg-blue-700 text-center">Rata Rata</td>
              </tr>

               @php
                $i = 0;
                  $startIndex = ($employees->currentPage() - 1) * $employees->perPage() + 1;
                  $totalInactiveSemester1Weight = 0;
                    $totalInactiveSemester2Weight = 0;
                    $totalInactiveSemester1WeightSum = 0;
                    $totalInactiveSemester2WeightSum = 0;
                    $totalInactiveSemester1DeptWeight = 0;
                    $totalInactiveSemester2DeptWeight = 0;
                    $totalInactiveWeightSum = 0;
                    $totalInactiveDeptWeightSum = 0;
                    $totalInactiveAverage = 0;
                    $totalInactiveAverageSum = 0;
                    $inactiveRowCount = 0;
              @endphp

              @foreach ($inactiveTargetEmployees as $index => $employee)
              @php
                  $i++;

                  
                $employeeId = $employee->employee_id;
                $departmentId = $employee->department_id;

                $sumInactiveSemester1 = $sumInactiveGroupSemester1[$employeeId] ?? 0;
                $sumInactiveSemester2 = $sumGroupInactiveSemester2[$employeeId] ?? 0;
                $totalInactiveSumEmployee = $totalSumInactiveSemester[$employeeId] ?? 0;
                $sumInactiveSemester1Dept = $sumGroupInactiveSemester1Dept[$departmentId] ?? 0;
                $sumInactiveSemester2Dept = $sumGroupInactiveSemester2Dept[$departmentId] ?? 0;
                $totalInactiveSumDept = $totalSumInactiveSemesterDept[$departmentId] ?? 0;
                $totalAllInactive = ($totalInactiveSumEmployee ?? 0) + ($totalInactiveSumDept ?? 0);

                $totalInactiveSemester1Weight = PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($sumInactiveSemester1);
                $totalInactiveSemester2Weight =  PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($sumInactiveSemester2);
                $totalInactiveSemester1DeptWeight =  PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($sumInactiveSemester1Dept);
                $totalInactiveSemester2DeptWeight =  PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($sumInactiveSemester2Dept);
                $totalInactiveWeightSum =  PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($totalInactiveSumEmployee);
                $totalInactiveDeptWeightSum = PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($totalInactiveSumDept);
                $totalInactiveAverage = PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages::average($totalAllInactive);

                $totalInactiveSemester1WeightSum += $totalInactiveSemester1Weight;
                $totalInactiveSemester2WeightSum += $totalInactiveSemester2Weight;
                $totalInactiveAverageSum += $totalInactiveAverage;
                $inactiveRowCount++;
                  
              @endphp
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ $startIndex + $index }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">{{ $employee->dept }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2" >{{ $employee->nik }}</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2">     
                    {{ $employee->name }}
                </td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2" >{{ $employee->occupation }}</td>
                @if ($sumInactiveSemester1Dept > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($sumInactiveSemester1Dept, 1) }}%</td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @if ($sumInactiveSemester2Dept > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($sumInactiveSemester2Dept, 1) }}%</td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif
                
                @if ($totalInactiveSumDept > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalInactiveSumDept, 1) }}%</td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @if ($sumInactiveSemester1 > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($sumInactiveSemester1, 1) }}%</td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @if ($sumInactiveSemester2 > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($sumInactiveSemester2, 1) }}%</td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                @if ($totalInactiveSumEmployee > 0)
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalInactiveSumEmployee, 1) }}%</td>
                @else
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                @endif

                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="{{ $i % 2 === 0 ? 'FFF2F2F2' : 'FFFFFFFF' }}" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalAllInactive, 1) }}%</td>
              </tr>
              @endforeach
                            @if(request()->query('department'))
              @php
              $finalInactiveTotalSemester1Weight = $inactiveRowCount > 0 ? $totalInactiveSemester1WeightSum / $inactiveRowCount : 0;
              $finalInactiveTotalSemester2Weight = $inactiveRowCount > 0 ? $totalInactiveSemester2WeightSum / $inactiveRowCount : 0;
              $finalInactiveTotalEmployeeWeight = $finalInactiveTotalSemester1Weight + $finalTotalSemester2Weight;
              $finalInactiveTotalAverage = $inactiveRowCount > 0 ? $totalInactiveAverageSum / $inactiveRowCount : 0;
              @endphp
              <tr class="bg-gray-200">
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center"></td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center" colspan="4">Total</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalInactiveSemester1DeptWeight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($totalInactiveSemester2DeptWeight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">
                    {{ number_format($totalInactiveDeptWeightSum, 1) }}%
                </td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($finalInactiveTotalSemester1Weight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($finalInactiveTotalSemester2Weight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($finalInactiveTotalEmployeeWeight, 1) }}%</td>
                <td data-b-a-s="thin" data-a-h="center" data-a-v="middle" data-a-wrap="true" data-fill-color="FFF2F2F2" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-600 py-0.5 px-2 text-center">{{ number_format($finalInactiveTotalAverage, 1) }}%</td>
            </tr>
            @endif
        </table>
    </div>
    @endif
    
    {{-- Pagination --}}
    <div class="shadow-lg shadow-black/15 mb-2 mt-3">
        <div class="flex w-full items-center justify-between border-t border-gray-200 bg-white px-10 py-3 rounded-md">
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
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        @if ($employees->onFirstPage())
                            <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-default">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $employees->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
    
                        @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                            @if ($page == $employees->currentPage())
                                <span aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $page }}</span>
                            @elseif ($page == 1 || $page == $employees->lastPage() || ($page >= $employees->currentPage() - 1 && $page <= $employees->currentPage() + 1))
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                            @elseif ($page == $employees->currentPage() - 2 || $page == $employees->currentPage() + 2)
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 cursor-default">...</span>
                            @endif
                        @endforeach
    
                        @if ($employees->hasMorePages())
                            <a href="{{ $employees->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @else
                            <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-default">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @endif
                    </nav>
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

    button.addEventListener("click", e => {
    let table = document.querySelector("#exportTable");
    TableToExcel.convert(table, {
            name: "summary-kpi-employee-report.xlsx",
            sheet: {
                name: "Sheet 1"
            }
        });
    });

    button2.addEventListener("click", e => {
        let table = document.querySelector("#exportTable2");
        TableToExcel.convert(table, {
            name: "summary-kpi-employee-report-inactive.xlsx",
            sheet: {
                name: "Sheet 1"
            }
        });
    });
</script>