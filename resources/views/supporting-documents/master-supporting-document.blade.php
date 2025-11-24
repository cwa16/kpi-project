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
                <span class="font-bold text-2xl">Master Data Pendukung</span>
            </div>

            <div class="flex justify-end">
                <div class="mt-0 rounded-md mb-1 mx-2">
                    <button type="button" class="p-2 bg-blue-600 my-2 rounded-md text-white">
                        <a href="{{ route('inputMasterSupportingDocument') }}" id="employee-link-">
                            Input Data Pendukung
                        </a>
                    </button>
                </div>
            </div>
        </div>


        <div class="flex justify-center mt-2 mb-2">
            <table class="w-3/4 table-auto">
                <tr>
                    <th style="width: 3%;"
                        class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        No.</th>
                    <th style="width: 15%"
                        class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        No. KPI</th>
                    <th style="width: 30%"
                        class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Nama KPI</th>
                    <th style="width: 20%"
                        class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Aksi</th>
                </tr>
                @php
                    $i = 0;
                @endphp
                @forelse ($data as $dt)
                    @php
                        $i++;
                    @endphp
                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100' }}">
                        <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                            {{ $i }}</td>
                        <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $dt->no_kpi }}
                        </td>
                        <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $dt->nama_kpi }}
                        </td>
                        <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0">
                            <div class="flex justify-center gap-3 my-0.5">
                                <a href="{{ route('showMasterSupportingDocument', ['id' => $dt->id]) }}"
                                    id="employee-link-{{ $dt->id }}" target="_blank" {{-- Penting: Membuka PDF di tab baru --}}
                                    class="rounded-md text-blue-500 hover:underline">View</a>
                                <a href="{{ route('editMasterSupportingDocument', ['id' => $dt->id]) }}"
                                    id="employee-link-{{ $dt->id }}"
                                    class="rounded-md text-green-500 hover:underline">Edit</a>
                                <a href="{{ route('deleteMasterSupportingDocument', ['id' => $dt->id]) }}"
                                    id="employee-link-{{ $dt->id }}"
                                    class="rounded-md text-red-500 hover:underline">Delete</a>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white">
                        <td colspan="5"
                            class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                            Tidak Ditemukan</td>
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
