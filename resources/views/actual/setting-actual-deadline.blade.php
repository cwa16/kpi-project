<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        @php
        $departmentID = request()->query('department');
        $year = request()->query('year');
        $semester = request()->query('semester');
        $employeeID = auth()->user()->id;
        $allStatus = request()->query('all');
        @endphp
        <form id="importForm" action="{{ route('actual.updateDeadline') }}" method="POST">
            @csrf
            @method('PUT')
        <div class="p-1 flex justify-center">
            <div class="mt-1 rounded-md border">
                <div class="">
                    <span class="mx-20 font-semibold">Tambah Batas Penginputan KPI</span>
                </div>
                <div class="flex justify-center">
                    <p>Pilih Tanggal</p>
                </div>
            <div class="flex justify-center">
                <select name="deadline" id="deadline" class="col-start-1 row-start-1 w-1/2 appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    <option value="">-- Pilih Deadline --</option>
                   @for ($day = 15; $day <= 31; $day++)
                   <option value="{{ $day }}">{{ $day }}</option>
                   @endfor
                </select>
            </div>
            <div class="flex justify-center mt-2">
                    <p>Pilih Bulan Penginputan</p>
                </div>
            <div class="flex justify-center mb-2">
                <select name="month" id="month" class="col-start-1 row-start-1 w-1/2 appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    <option value="">-- Pilih Bulan --</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <input type="hidden" name="year" id="year" value="{{ $year }}">
              <div class="mb-2 mt-0 flex justify-center">
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md">Submit</button>
                </div>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
        </div>
        </form>

        <div class="flex justify-center">
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md" onclick="history.back();">
                Back
            </button>
            
        </div>
    </div>

    <script>
        document.getElementById('importForm').addEventListener('submit', function() {
            var submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Loading...'; // Optional: Show loading text
        });
    </script>
</x-app-layout>