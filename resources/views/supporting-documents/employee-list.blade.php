<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        @php
        $currentYear = Carbon\Carbon::now()->year;
            $startYear = 2024;
            $endYear = $currentYear + 2;
            $role = auth()->user()->role;
        @endphp
        <div class="flex justify-between">
            <div class="">
                <span class="font-bold text-2xl">Lihat Data Pendukung</span>
            </div>
            <div class="flex flex-col justify-end">
                <div class="flex justify-end">
                    <div class=" mt-1 rounded-md">
                        <form action="{{ route('supportingDocumentEmployee') }}" method="GET">
                        <div class="mt-1 mb-1 mx-2">
                            <select name="department" id="department" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="">-- Departemen --</option>
                                @foreach ($departments as $item )
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center">
                        </div>
                    </div>
                    <div class="mt-0 rounded-md mb-1 mx-2">
                        <button type="submit" class="p-2 bg-blue-600 my-2 rounded-md text-white">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
                    <div class="flex justify-end">
                        <div class="relative mt-1 rounded-md">
                            <div class="mt-2 mb-1 mx-0.5">
                                <select name="year" id="year" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="">-- Tahun --</option>
                                    @for ($year = $startYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                          </div>
                          <div class="mt-0 rounded-md mb-1 mx-2">
                            <button type="button" class="p-2 bg-blue-600 my-2 rounded-md text-white">
                                <a href="{{ route('supportingDocumentDept') }}" id="employee-link-">
                                    Data Pendukung Dept
                                </a>
                            </button>
                        </div>
                    </div>
            </div>
        </div>


        <div class="flex justify-center mt-2 mb-2">
            <table class="w-3/4 table-auto">
                <tr>
                    <th style="width: 3%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                    <th style="width: 15%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">NIK</th>
                    <th style="width: 30%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Nama</th>
                    <th style="width: 30%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Dept</th>
                    <th style="width: 20%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Aksi</th>
                </tr>
                @php
                    $i = 0;
                @endphp
                @forelse ($employees as $employee)
                    @php
                        $i++
                    @endphp
                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                        <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">{{ $i }}</td>
                        <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $employee->nik }}</td>
                        <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $employee->name }}</td>
                        <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $employee->department }}</td>
                        <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">
                            <div class="flex justify-center gap-3 my-0.5">
                                <a href="{{ route('employeeSupportingDocumentList') }}?employee={{ $employee->id }}" id="employee-link-{{ $employee->id }}" class="rounded-md text-blue-500 hover:underline">Lihat Data Pendukung</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white">
                        <td colspan="5" class="border border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">Tidak Ditemukan</td>
                    </tr>
                @endforelse


            </table>
        </div>

    </div>
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearDropdown = document.getElementById('year');

        // Set the dropdown values from localStorage if they exist
        const savedYear = localStorage.getItem('selectedYear');
        if (savedYear) {
            yearDropdown.value = savedYear;
        }

        // Save the dropdown values to localStorage on change
        yearDropdown.addEventListener('change', function() {
            const year = this.value;
            localStorage.setItem('selectedYear', year);
            updateLinks();
        });


        function updateLinks() {
            const year = yearDropdown.value;
            const links = document.querySelectorAll('a[id^="employee-link-"]');
            links.forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('year', year);
                link.href = url.toString();
            });
        }

        // Initial update of links
        updateLinks();
    });
</script>
