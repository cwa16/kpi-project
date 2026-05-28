<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        @php
        $i = 0;
        $currentYear = Carbon\Carbon::now()->year;
        $startYear = 2024;
        $endYear = $currentYear + 2;
        @endphp
        <div class="flex justify-between">
        <div class="p-0">
            <span class="font-bold text-2xl">Summary KPI Departemen</span>
        </div>
        <div class="flex justify-end items-center mb-2">
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
        </div>
    </div>

        <div class="flex justify-center">
        <table class="w-1/2">
            <tr>
                <th style="width: 3%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                <th class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Departemen</th>
                <th class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Kode Departemen</th>
                <th class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Aksi</th>
            </tr>

            @forelse ($deptList as $department)
            @php
            $i++
            @endphp
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">{{ $i }}</td>
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $department->name }}</td>
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $department->code }}</td>
                <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">
                    <div class="flex justify-center gap-2 text-[12px]">
                        <a id="employee-link-{{ $department->id }}" href="{{ route('report-year.department', $department->id) }}?semester=&year=">
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
