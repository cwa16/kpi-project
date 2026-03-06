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
                <span class="font-bold text-2xl">Master Data Pendukung Dept.</span>
            </div>

            <div class="flex justify-end items-center gap-3 mb-4">
                {{-- Tombol Kembali (Secondary/Ghost Style) --}}
                <a href="{{ route('masterSupportingDocument') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-800 transition-all duration-200 shadow-sm">
                    Kembali
                </a>

                {{-- Tombol Input (Primary Style) --}}
                <a href="{{ route('inputMasterSupportingDocumentDept') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 active:ring-2 active:ring-blue-300 transition-all duration-200 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Data Pendukung Dept.
                </a>
            </div>
        </div>


        <div class="flex justify-center mt-2 mb-2">
            <table id="myTable" class="w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th
                            class="border border-gray-300 text-[14px] tracking-wide font-semibold text-white py-2 px-4 bg-blue-700">
                            No.</th>
                        <th
                            class="border border-gray-300 text-[14px] tracking-wide font-semibold text-white py-2 px-4 bg-blue-700">
                            Nama KPI</th>
                        <th
                            class="border border-gray-300 text-[14px] tracking-wide font-semibold text-white py-2 px-4 bg-blue-700">
                            Nama Data Pendukung</th>
                        <th
                            class="border border-gray-300 text-[14px] tracking-wide font-semibold text-white py-2 px-4 bg-blue-700">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $dt)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="border border-gray-300 text-[12px] px-2 py-2 text-center">{{ $loop->iteration }}
                            </td>
                            <td class="border border-gray-300 text-[12px] px-2 py-2">{{ $dt->nama_kpi }}</td>
                            <td class="border border-gray-300 text-[12px] px-2 py-2">{{ $dt->nama_file }}</td>
                            <td class="border border-gray-300 text-[12px] px-2 py-2">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('showMasterSupportingDocumentDept', ['id' => $dt->id]) }}"
                                        target="_blank"
                                        class="px-3 py-1 bg-blue-500 text-white rounded shadow-sm hover:bg-blue-600 transition">View</a>

                                    @if (in_array($auth_dept, [6, 3, 9]))
                                        <a href="{{ route('editMasterSupportingDocumentDept', ['id' => $dt->id]) }}"
                                            class="px-3 py-1 bg-green-500 text-white rounded shadow-sm hover:bg-green-600 transition">Edit</a>
                                        <a href="{{ route('deleteMasterSupportingDocumentDept', ['id' => $dt->id]) }}"
                                            onclick="return confirm('Yakin ingin menghapus?')"
                                            class="px-3 py-1 bg-red-500 text-white rounded shadow-sm hover:bg-red-600 transition">Delete</a>
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
</x-app-layout>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "pageLength": 10,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json", // Bahasa Indonesia
                "search": "Cari Data:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": 3
                } // Matikan sorting untuk kolom Aksi
            ],
            "dom": '<"flex justify-between mb-4"lf>rt<"flex justify-between mt-4"ip>' // Layouting sederhana
        });
    });
</script>

<style>
    /* Merapikan sedikit style default datatables agar masuk ke gaya Tailwind */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        margin-left: 0.5rem;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        padding: 0.25rem 1.5rem 0.25rem 0.5rem;
    }

    table.dataTable thead th {
        border-bottom: none !important;
    }

    table.dataTable td {
        border-bottom: 1px solid #e2e8f0 !important;
    }
</style>


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
