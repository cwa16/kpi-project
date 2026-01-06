<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        @php
            $currentYear = Carbon\Carbon::now()->year;
            $startYear = 2024;
            $endYear = $currentYear + 2;
            $role = auth()->user()->role;
            $departmentQuery = request()->query('department');
            $employeeQuery = request()->query('employee');
            $currentMonth = Carbon\Carbon::now()->month;

            if ($departmentQuery == 'all') {
                $all = 'true';
            } elseif ($departmentQuery) {
                $all = 'dept';
            } elseif ($employeeQuery) {
                $all = 'employee';
            } else {
                $all = 'true';
            }

        @endphp
        <div class="flex justify-between">
            <div class="p-0">
                <span class="font-bold text-2xl">Input Target Employee & Upload Program</span>
            </div>
            <div class="flex justify-end">
                <div class="flex flex-col">
                    @if ($role != '')
                    <div class="flex justify-end">
                        <div class="relative mt-1 rounded-md">
                            <form action="{{ route('target.department') }}" method="GET">
                            <div class="mt-1 mb-1 mx-2">
                                <select name="department" id="department" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="">-- Departemen --</option>
                                    @if ($role == 'Approver' || $role == 'Mng Approver')
                                    <option value="all">All Dept</option>
                                    @endif
                                    @foreach ($deptList as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center">
                            </div>
                        </div>
                        @if ($role == 'Approver' || $role == 'Mng Approver')
                        <div class="relative mt-1 rounded-md">
                            <div class="mt-1 mb-1 mx-2">
                                <select name="status" id="status" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="">-- Status --</option>
                                    @foreach ($statusList as $item)
                                    <option value="{{ $item->status }}">{{ $item->status }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center">
                            </div>
                        </div>
                        @endif
                        <div class="mt-0 rounded-md mb-1">
                            <button type="submit" class="p-2 bg-blue-600 my-2 rounded-md text-white">
                                Filter
                            </button>
                        </form>
                        </div>
                    </div>
                    @endif
                    <div class="flex justify-end">
                        <div class="relative mt-1 rounded-md">
                            <div class="mt-2 mb-1 mx-2">
                                <select name="year" id="year" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="">-- Tahun --</option>
                                    @for ($year = $startYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center">
                            </div>
                          </div>
                          <div class="relative mt-1 rounded-md">
                            <div class="mt-2 mb-1 mx-2">
                                <select name="semester" id="semester" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="">-- Semester --</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center">
                            </div>
                          </div>
                          <div class="relative mt-1 rounded-md mb-1">
                            <button class="p-2 bg-blue-600 my-2 rounded-md">
                                @if ($departmentQuery == 'all')
                                <a id="input-target-link" href="{{ route('target.departmentAll') }}?year=&semester=&all={{ $all }}" >
                                    <span class="text-white">List Target Department</span>
                                  </a>
                                  @else
                                  <a id="input-target-link" href="{{ route('target.showDeptOne', 'department=' . $departments->first()->department_id ?? '' ) }}&all={{ $all }}" >
                                      <span class="text-white">Input Target Dept</span>
                                    </a>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="mt-0">
            @if ($role == 'Approver')
            <div class="relative mt-0 rounded-md mb-1">
                <button class="p-2 bg-blue-600 my-0 rounded-md">
                    <a id="set-deadline-link" href="{{ route('target.settingTargetDeadline') }}">
                        <i class="ri-settings-2-line text-lg text-white"></i>
                        <span class="text-white">Setting Deadline Target KPI</span>
                    </a>
                </button>
            </div>
            @endif
        </div>
        <div class="flex justify-center">
        <table class="w-[1500px] table-fixed">
            <tr>
                <th style="width: 4%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                <th style="width: 9%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">NIK</th>
                <th style="width: 22%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Nama</th>
                <th style="width: 14%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Department</th>
                <th style="width: 8%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Jabatan</th>
                <th style="width: 40%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Opsi</th>
                <th style="width: 8%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Upload Program</th>
            </tr>
            @php
                $i = 0;
            @endphp
            @forelse ($departments as $department)
            @php
            $i++
            @endphp
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">

                <td style="width: 3%" class="border border-gray-400 tracking-wide text-[12px] px-2 py-0 text-center">{{ $i }}</td>
                <td class="border border-gray-400 tracking-wide text-[12px] px-2 py-0">{{ $department->nik }}</td>
                <td style="width: 28%" class="border border-gray-400 tracking-wide text-[12px] px-2 py-0">{{ $department->employee }}</td>
                <td class="border border-gray-400 tracking-wide text-[12px] px-2 py-0">{{ $department->department }}</td>
                <td class="border border-gray-400 tracking-wide text-[12px] px-2 py-0">{{ $department->occupation }}</td>
                <td class="border border-gray-400 tracking-wide text-[12px] px-2 py-0">
                    <div class="flex justify-center gap-3 my-0.5">
                        <button class="bg-blue-500 px-2 rounded-sm my-1">
                            <a id="employee-link-{{ $department->employee_id }}" href="{{ route('target.show', 'employee=' . $department->employee_id) }}&department={{ $department->department_id }}&all={{ $all }}&status=">
                              <span class="text-white hover:underline">Lihat Target</span>
                            </a>
                        </button>
                        @php
                        $now = Carbon\Carbon::now();
                        @endphp
                        @if (($now > $deadline->start_date && $now < $deadline->end_date) || $role == 'Approver')
                        <button class="bg-green-600 px-2 rounded-sm my-1">
                            <a id="employee-link-{{ $department->employee_id }}" href="{{ route('target.showImport') }}?employee={{ $department->employee_id }}&all={{ $all }}&department={{ $department->department_id }}">
                                <span class="text-white hover:underline">Upload Excel</span>
                            </a>
                        </button>
                        @endif
                </div>
                </td>
                <td class="border text-[12px] border-gray-400 tracking-wide px-2 text-center">
                    @if(!$department->file)
                    <a href="{{ route('action-plan.addEmployeeFile', $department->employee_id) }}">
                        <i class="ri-add-line bg-green-600 text-white text-sm p-0.5 rounded-sm"></i>
                       </a>
                     @endif
                    @if ($department->file)
                   <a href="{{ route('action-plan.showFile', $department->file) }}" target="_blank">
                    <i class="ri-eye-fill text-sm p-0.5 bg-blue-600 text-white rounded-sm"></i>
                   </a>
                   <a href="{{ route('action-plan.editFile', $department->action_plan_id) }}">
                    <i class="ri-edit-box-line p-0.5 text-sm bg-yellow-400 text-white rounded-sm"></i>
                   </a>
                   @endif
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
    {{-- Pagination --}}
    <div class="shadow-lg shadow-black/15 mb-2 mt-3 ml-64">
        <div class="flex w-full items-center justify-between border-t border-gray-200 bg-white px-10 py-3 rounded-md">
            <div class="flex flex-1 justify-between sm:hidden">
                {{ $departments->links() }}
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $departments->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $departments->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $departments->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        @if ($departments->onFirstPage())
                            <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-default">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $departments->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif

                        @foreach ($departments->getUrlRange(1, $departments->lastPage()) as $page => $url)
                            @if ($page == $departments->currentPage())
                                <span aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $page }}</span>
                            @elseif ($page == 1 || $page == $departments->lastPage() || ($page >= $departments->currentPage() - 1 && $page <= $departments->currentPage() + 1))
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                            @elseif ($page == $departments->currentPage() - 2 || $page == $departments->currentPage() + 2)
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 cursor-default">...</span>
                            @endif
                        @endforeach

                        @if ($departments->hasMorePages())
                            <a href="{{ $departments->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
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
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearDropdown = document.getElementById('year');
        const semesterDropdown = document.getElementById('semester');
        const inputTargetLink = document.getElementById('input-target-link');

        // Set the dropdown values from localStorage if they exist
        const savedYear = localStorage.getItem('selectedYear');
        const savedSemester = localStorage.getItem('selectedSemester');
        if (savedYear) {
            yearDropdown.value = savedYear;
        }
        if (savedSemester) {
            semesterDropdown.value = savedSemester;
        }

        // Function to update links
        function updateLinks() {
            const year = yearDropdown.value;
            const semester = semesterDropdown.value;

            // Update employee links
            const employeeLinks = document.querySelectorAll('a[id^="employee-link-"]');
            employeeLinks.forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('year', year);
                url.searchParams.set('semester', semester);
                link.href = url.toString();
            });

            const url = new URL(inputTargetLink.href);
            url.searchParams.set('year', year);
            url.searchParams.set('semester', semester);
            inputTargetLink.href = url.toString();

        }

        // Save the dropdown values to localStorage on change
        yearDropdown.addEventListener('change', function() {
            localStorage.setItem('selectedYear', this.value);
            updateLinks();
        });

        semesterDropdown.addEventListener('change', function() {
            localStorage.setItem('selectedSemester', this.value);
            updateLinks();
        });

        // Initial update of links
        updateLinks();
    });
</script>
