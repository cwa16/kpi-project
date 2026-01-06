<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        <div class="p-0">
            <span class="font-bold text-2xl">Daftar Data Pendukung Karyawan</span>
        </div>


        @php
        $months = [
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];
        @endphp

        <div class="grid grid-cols-4">
            <div class="p-0">
                <table class="w-full">
                    <tr>
                        <td style="width: 10%" class="text-sm">NIK</td>
                        <td style="width: 2%" class="text-sm px-2">:</td>
                        <td class="text-sm">{{ $employee->nik ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm">Departemen</td>
                        <td class="text-sm px-2">:</td>
                        <td class="text-sm">{{ $employee->department ?? '' }}</td>
                    </tr>
                </table>
            </div>
            <div class="p-0">
                <table class="w-full">
                    <tr>
                        <td style="width: 10%" class="text-sm">Nama</td>
                        <td style="width: 2%" class="text-sm px-2">:</td>
                        <td class="text-sm">{{ $employee->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm">Posisi</td>
                        <td class="text-sm px-2">:</td>
                        <td class="text-sm">{{ $employee->occupation ?? '' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="p-0 mt-2">
            <button type="button" class="p-2 bg-blue-500 text-white rounded-md" onclick="history.back()">
                Back
            </button>
        </div>

        <div class="mt-2">
            <table class="table-auto">
                <tr>
                    <th style="width: 3%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                    <th style="width: 40%" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Item KPI</th>

                    @foreach ($months as $month)
                    <th style="width: 4%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        {{ $month }}
                    </th>
                    @endforeach
                </tr>
                @php
                    $i = 0;
                @endphp
                @forelse ($targets as $target)
                @php
                    $i++;
                @endphp
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                    <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">{{ $i }}</td>
                    <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0">{{ $target->indicator }}</td>
                    @foreach ($months as $month => $monthName)
                    @php
                        $targetUnitField = 'target_' . $month;
                        $actual = $actuals->first(function ($item) use ($target, $month) {
                            return \Carbon\Carbon::parse($item->date)->format('m') == $month && $item->kpi_item == $target->indicator;
                        });
                    @endphp
                    @if ($target->$targetUnitField !== null)
                    @if ($actual)
                    <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        <button id="open-modal-{{ $actual->id }}" data-month="{{ $month }}" data-actual-id="{{ $actual->id }}" data-file="{{ asset('record_files/' . $actual->record_file) }}" class="open-modal">
                            <i class="ri-eye-fill text-sm p-0.5  text-blue-500 rounded-sm"></i>
                        </button>
                    </td>
                    <div class="hidden fixed inset-0 justify-center" id="modal-{{ $actual->id }}">
                        <div class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center h-screen bg-gray-900 bg-opacity-50">
                            <div class="bg-white rounded-lg shadow-lg p-4 h-[700px] w-[700px]">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-semibold">Data Pendukung</h2>
                                    <button id="close-modal-{{ $actual->id }}" class="text-gray-500 hover:text-gray-700">&times;</button>
                                </div>
                                <div class="">
                                    <span id="fileNumber-modal-{{ $actual->id }}" class="text-[12px] tracking-wide font-medium text-gray-600 mb-1"></span>
                                </div>
                                <div class=" flex justify-center items-center">
                                    <object id="pdfObject-{{ $actual->id }}" data="" type="application/pdf" width="100%" height="500px"></object>
                                </div>
                            </div>
                        </div>

                    </div>
                    @else
                    <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">

                    </td>
                    @endif
                    @else
                    <td class="border border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        <span class="text-red-500">N/A</span>
                    </td>
                    @endif
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td class="border border-gray-400 text-[16px] tracking-wide px-2 py-0 text-center" colspan="20">Data tidak ditemukan</td>
                </tr>
                @endforelse

            </table>
        </div>



    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Open modal
    document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', function () {
            const actualId = this.dataset.actualId;
            const fileUrl = this.dataset.file;

            // Get modal and object elements
            const modal = document.getElementById(`modal-${actualId}`);
            const pdfObject = document.getElementById(`pdfObject-${actualId}`);

            // Set the PDF file URL
            pdfObject.setAttribute('data', fileUrl);

            // Show the modal
            modal.classList.remove('hidden');
        });
    });

    // Close modal
    document.querySelectorAll('[id^="close-modal-"]').forEach(button => {
        button.addEventListener('click', function () {
            const actualId = this.id.split('-').pop();
            const modal = document.getElementById(`modal-${actualId}`);

            // Hide the modal
            modal.classList.add('hidden');
        });
    });
});
</script>
