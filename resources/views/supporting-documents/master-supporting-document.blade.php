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

            <div class="flex justify-end items-center gap-3 mb-4">
                {{-- Tombol Kembali (Secondary/Ghost Style) --}}
                <a href="{{ route('masterSupportingDocument') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-800 transition-all duration-200 shadow-sm">
                    Kembali
                </a>

                {{-- Tombol Input (Primary Style) --}}
                <a href="{{ route('inputMasterSupportingDocument') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 active:ring-2 active:ring-blue-300 transition-all duration-200 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Data Pendukung
                </a>
            </div>
        </div>


        <div class="flex justify-center mt-2 mb-2">
            <div class="overflow-x-auto p-4 bg-white rounded-lg shadow">
                <table id="tableIndividu" class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-blue-700 text-white">
                            <th class="border border-gray-400 px-4 py-2 text-sm font-semibold tracking-wide w-[5%]">No.
                            </th>
                            <th class="border border-gray-400 px-4 py-2 text-sm font-semibold tracking-wide w-[20%]">
                                Dept.</th>
                            <th class="border border-gray-400 px-4 py-2 text-sm font-semibold tracking-wide w-[30%]">
                                Nama KPI</th>
                            <th class="border border-gray-400 px-4 py-2 text-sm font-semibold tracking-wide w-[30%]">
                                Nama Data Pendukung</th>
                            <th class="border border-gray-400 px-4 py-2 text-sm font-semibold tracking-wide w-[15%]">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($data as $dt)
                            <tr class="hover:bg-blue-50 transition-colors duration-150">
                                <td class="border border-gray-300 px-3 py-2 text-xs text-center font-medium">
                                    {{ $loop->iteration }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-xs">{{ $dt->dept }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-xs">{{ $dt->nama_kpi }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-xs font-mono text-blue-600">
                                    {{ $dt->nama_file }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-xs">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('showMasterSupportingDocument', ['id' => $dt->id]) }}"
                                            target="_blank"
                                            class="bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-600 hover:text-white transition shadow-sm">
                                            View
                                        </a>
                                        @if (in_array($auth_dept, [6, 3, 9]))
                                            <a href="{{ route('editMasterSupportingDocument', ['id' => $dt->id]) }}"
                                                class="bg-green-100 text-green-600 px-2 py-1 rounded hover:bg-green-600 hover:text-white transition shadow-sm">
                                                Edit
                                            </a>
                                            <a href="{{ route('deleteMasterSupportingDocument', ['id' => $dt->id]) }}"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                                class="bg-red-100 text-red-600 px-2 py-1 rounded hover:bg-red-600 hover:text-white transition shadow-sm">
                                                Delete
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tableIndividu').DataTable({
            "pageLength": 10,
            "responsive": true,
            "language": {
                "search": "Cari KPI / Dept:",
                "lengthMenu": "_MENU_ baris",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
                "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Berikutnya"
                }
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": 4
                } // Mematikan sorting pada kolom 'Aksi'
            ]
        });
    });
</script>


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
