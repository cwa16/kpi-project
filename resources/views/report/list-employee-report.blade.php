<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        @php
        $currentYear = Carbon\Carbon::now()->year;
        $startYear = 2026;
        $endYear = $currentYear + 2;
        $role = auth()->user()->role;
        $email = auth()->user()->email;
        $departmentQuery = request()->query('department');
        $statusQuery = request()->query('status');
        @endphp
        <div class="flex justify-between">
            <div class="p-0">
                <span class="font-bold text-2xl">Summary KPI Employees</span>
            </div>
            @if ($role != 'Inputer' || $role != '')
            <div class="flex flex-col">
                <div class="flex justify-end">
                    <div class="relative mt-1 rounded-md">
                        <form action="{{ route('report-year.index') }}" method="GET">
                        <div class="mt-1 mb-1 mx-2">
                            <select name="department" id="department" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="">-- Departmen --</option>
                                @if ($role == 'Approver' || $email == 'johari@bskp.co.id' || $email == 'surya-sp@bskp.co.id')
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
                        <form action="{{ route('report-year.index') }}" method="GET">
                        <div class="mt-1 mb-1 mx-2">
                            <select name="status" id="status" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="">-- Status --</option>
                                @foreach ($listStatus as $item)
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
            </div>
            @endif
            </div>

        <div class="flex justify-end items-center mb-2">
            <div class="relative mt-1 rounded-md">
                <div class="mt-1 mb-1 mx-2">
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
                <div class="mt-1 mb-1 mx-2">
                    <select name="semester" id="semester" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        <option value="">-- Semester --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center">
                </div>
              </div>
        </div>
        <div class="flex justify-center">
        <table class="w-[1100px] table-auto">
            <tr>
                <th style="width: 5%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                <th style="width: 20%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">NIK</th>
                <th style="width: 28%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Nama</th>
                <th style="width: 14%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Department</th>
                <th style="width: 20%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Jabatan</th>
                <th class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Aksi</th>
            </tr>

            @php

            $employeeQuery = request()->query('employee');

            if (!$employeeQuery) {
                $startIndex = ($departments->currentPage() - 1) * $departments->perPage();
            } else {
                $i = 0;
            }
            @endphp

            @forelse ($departments as $department)
            @php
            if (!$employeeQuery) {
             $i = $loop->index + 1 + $startIndex;
            } else {
            $i++;
            }
            @endphp
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">{{ $i }}</td>
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $department->nik }}</td>
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $department->employee }}</td>
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $department->department }}</td>
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $department->occupation }}</td>
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">
                    <div class="flex justify-center gap-2 text-[12px]">
                        <a id="employee-link-{{ $department->employee_id }}" href="{{ route('report-year.show', $department->employee_id) }}?semester=&year=">
                            <span class="hover:underline text-blue-600">Summary</span>
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
        {{-- Pagination --}}
        @if ($departmentQuery == 'all' || $statusQuery != 'Manager')
        <div class="shadow-lg shadow-black/15 mb-2 mt-3">
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
        @endif
        {{-- end of pagination --}}
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearDropdown = document.getElementById('year');
        const semesterDropdown = document.getElementById('semester');

        // Set the dropdown values from localStorage if they exist
        const savedYear = localStorage.getItem('selectedYear');
        const savedSemester = localStorage.getItem('selectedSemester');
        if (savedYear) {
            yearDropdown.value = savedYear;
        }
        if (savedSemester) {
            semesterDropdown.value = savedSemester;
        }

        // Save the dropdown values to localStorage on change
        yearDropdown.addEventListener('change', function() {
            const year = this.value;
            localStorage.setItem('selectedYear', year);
            updateLinks();
        });

        semesterDropdown.addEventListener('change', function() {
            const semester = this.value;
            localStorage.setItem('selectedSemester', semester);
            updateLinks();
        });

        function updateLinks() {
            const year = yearDropdown.value;
            const semester = semesterDropdown.value;
            const links = document.querySelectorAll('a[id^="employee-link-"]');
            links.forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('year', year);
                url.searchParams.set('semester', semester);
                link.href = url.toString();
            });
        }

        // Initial update of links
        updateLinks();
    });
</script>
