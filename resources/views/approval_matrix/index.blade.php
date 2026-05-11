<x-app-layout :title="$title" :desc="$desc">
    {{-- CSS Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            border-color: #d1d5db !important;
            border-radius: 0.375rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
            color: #4b5563 !important;
            font-size: 0.875rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            outline: none !important;
            border-color: #9ca3af !important;
            border-radius: 0.25rem !important;
        }
    </style>

    <div class="ml-64 mt-4 overflow-x-auto p-4 bg-gray-100 border border-gray-200 shadow-md shadow-black/10 rounded-md">

        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-700">Approval Matrix KPI Department</h2>
            <p class="text-gray-500 text-sm">Atur PIC (Employee) yang berhak melakukan Check / Approve untuk
                masing-masing item KPI secara global.</p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Tambah Matrix --}}
        <div class="bg-white p-4 rounded-md shadow-sm border border-gray-300 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Tambah Hak Akses Approval</h3>
            <form action="{{ route('approval_matrix.store') }}" method="POST" class="flex flex-wrap items-end gap-4">
                @csrf
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih PIC (Karyawan)</label>
                    <select name="employee_nik" required class="select2-emp w-full">
                        <option value=""></option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->nik }}">{{ $emp->nik }} - {{ $emp->name }}
                                ({{ $emp->occupation }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Nama KPI</label>
                    <select name="kpi_name" required class="select2-kpi w-full">
                        <option value=""></option>
                        @foreach ($kpiNames as $kpi)
                            <option value="{{ $kpi->name }}">{{ $kpi->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-1/4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Approval</label>
                    <select name="approval_type" required
                        class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 h-[38px]">
                        <option value="Check 1">Check 1</option>
                        <option value="Check 2">Check 2</option>
                        <option value="Mng Approve">Manager Approve</option>
                        <option value="Approver">Final Check (HRD Spv)</option>
                    </select>
                </div>

                <div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 text-sm h-[38px]">
                        + Tambah PIC
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Matrix (Tampilan Matrix Asli) --}}
        <div class="bg-white rounded-md shadow-sm border border-gray-300 overflow-hidden mt-4">
            <div class="p-3 bg-gray-50 border-b border-gray-200">
                <span class="font-semibold text-gray-600">Daftar Matrix Approval Aktif</span>
            </div>
            <table class="w-full table-auto border-collapse">
                <thead class="bg-blue-700 text-white text-[13px] tracking-wide text-center">
                    <tr>
                        <th class="py-2 px-2 font-medium border border-gray-400 w-12">No</th>
                        <th class="py-2 px-4 font-medium border border-gray-400 w-1/4">Nama KPI (Indicator)</th>
                        <th class="py-2 px-2 font-medium border border-gray-400 w-1/6">Check 1</th>
                        <th class="py-2 px-2 font-medium border border-gray-400 w-1/6">Check 2</th>
                        <th class="py-2 px-2 font-medium border border-gray-400 w-1/6">Manager Approve</th>
                        <th class="py-2 px-2 font-medium border border-gray-400 w-1/6">Final Check</th>
                    </tr>
                </thead>
                <tbody class="text-[12px] text-gray-700">
                    @forelse ($kpis as $index => $kpi)
                        @php
                            // Ambil semua matriks (PIC) untuk KPI Baris ini
                            $kpiMatrices = $matrices->get($kpi->kpi_name) ?? collect();

                            // Pisahkan berdasarkan tipe approval
                            $check1List = $kpiMatrices->where('approval_type', 'Check 1');
                            $check2List = $kpiMatrices->where('approval_type', 'Check 2');
                            $mngList = $kpiMatrices->where('approval_type', 'Mng Approve');
                            $finalList = $kpiMatrices->where('approval_type', 'Approver');
                        @endphp

                        <tr
                            class="{{ $loop->even ? 'bg-blue-50' : 'bg-white' }} hover:bg-blue-100 transition duration-150">
                            <td class="py-2 px-2 border border-gray-400 text-center align-middle">
                                {{ $kpis->firstItem() + $index }}</td>
                            <td class="py-2 px-4 border border-gray-400 font-semibold text-gray-800 align-middle">
                                {{ $kpi->kpi_name }}</td>

                            {{-- Kolom CHECK 1 --}}
                            <td class="py-2 px-2 border border-gray-400 align-top">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    @foreach ($check1List as $item)
                                        <span
                                            class="inline-flex items-center bg-orange-100 text-orange-700 px-2 py-1 rounded text-[11px] font-bold border border-orange-300">
                                            {{ explode(' ', trim($item->employee->name ?? $item->employee_nik))[0] }}
                                            {{-- Ambil nama depan saja biar muat --}}
                                            <form action="{{ route('approval_matrix.destroy', $item->id) }}"
                                                method="POST" onsubmit="return confirm('Hapus PIC ini?');"
                                                class="ml-1 pl-1 border-l border-orange-300">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-orange-500 hover:text-red-700 font-bold focus:outline-none">&times;</button>
                                            </form>
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Kolom CHECK 2 --}}
                            <td class="py-2 px-2 border border-gray-400 align-top">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    @foreach ($check2List as $item)
                                        <span
                                            class="inline-flex items-center bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-[11px] font-bold border border-indigo-300">
                                            {{ explode(' ', trim($item->employee->name ?? $item->employee_nik))[0] }}
                                            <form action="{{ route('approval_matrix.destroy', $item->id) }}"
                                                method="POST" onsubmit="return confirm('Hapus PIC ini?');"
                                                class="ml-1 pl-1 border-l border-indigo-300">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-indigo-500 hover:text-red-700 font-bold focus:outline-none">&times;</button>
                                            </form>
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Kolom MNG APPROVE --}}
                            <td class="py-2 px-2 border border-gray-400 align-top">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    @foreach ($mngList as $item)
                                        <span
                                            class="inline-flex items-center bg-blue-100 text-blue-700 px-2 py-1 rounded text-[11px] font-bold border border-blue-300">
                                            {{ explode(' ', trim($item->employee->name ?? $item->employee_nik))[0] }}
                                            <form action="{{ route('approval_matrix.destroy', $item->id) }}"
                                                method="POST" onsubmit="return confirm('Hapus PIC ini?');"
                                                class="ml-1 pl-1 border-l border-blue-300">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-blue-500 hover:text-red-700 font-bold focus:outline-none">&times;</button>
                                            </form>
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Kolom FINAL CHECK --}}
                            <td class="py-2 px-2 border border-gray-400 align-top">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    @foreach ($finalList as $item)
                                        <span
                                            class="inline-flex items-center bg-green-100 text-green-700 px-2 py-1 rounded text-[11px] font-bold border border-green-300">
                                            {{ explode(' ', trim($item->employee->name ?? $item->employee_nik))[0] }}
                                            <form action="{{ route('approval_matrix.destroy', $item->id) }}"
                                                method="POST" onsubmit="return confirm('Hapus PIC ini?');"
                                                class="ml-1 pl-1 border-l border-green-300">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-green-600 hover:text-red-700 font-bold focus:outline-none">&times;</button>
                                            </form>
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 border border-gray-400 text-center text-gray-500 italic">
                                Belum ada data Approval Matrix yang diatur.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3 bg-white">
                {{ $kpis->links() }} {{-- Pagination pake var $kpis --}}
            </div>
        </div>

    </div>

    {{-- Script untuk jQuery dan Select2 --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-emp').select2({
                placeholder: "-- Cari Karyawan (Nama/NIK) --",
                allowClear: true,
                width: '100%'
            });
            $('.select2-kpi').select2({
                placeholder: "-- Cari Nama KPI --",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</x-app-layout>
