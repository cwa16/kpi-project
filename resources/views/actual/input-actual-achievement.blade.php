<x-app-layout :title="$title" :desc="$desc">
    @php
        $dateNow = \Carbon\Carbon::now()->format('d');
        $role = auth()->user()->role;
    @endphp
  <form id="achievementForm" action="{{ url('actual/input-actual-achievement/store') }}" method="POST" enctype="multipart/form-data">
    @csrf
  <div class="ml-64 mt-4 overflow-y-auto p-2 bg-gray-100 border border-gray-200 shadow-md shadow-black/10 rounded-md">
 
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-0">
      @foreach ($targets as $target)
        <div class="relative mt-1 rounded-md">
            <span class="pl-3 font-semibold">NIK</span>  
            <input type="text" name="nik" id="nik" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="NIK" value="{{ $target->nik }}" readonly>
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
        
        <div class="relative mt-1 rounded-md">
            <span class="pl-3 font-semibold">Departemen</span>  
          <input type="text" name="department_name" id="department_name" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Departemen" value="{{ $target->department }}" readonly>
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
        @php
        $months = [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        $formatKgValue = function ($value) {
                // Convert the value to a string
                $valueStr = (string) $value;

                // Find the position of the decimal point
                $decimalPos = strpos($valueStr, '.');

                // If there is no decimal point, return the value as is
                if ($decimalPos === false) {
                    return number_format($value);
                }

                // Get the number of digits before the decimal point
                $digitsBeforeDecimal = $decimalPos;

                // If there are more than 3 digits before the decimal point, return the value as is
                if ($digitsBeforeDecimal > 3) {
                    return number_format($value);
                }

                // Otherwise, format the value with 1 decimal place
                return number_format($value, 3);
            }
        @endphp
        <div class="relative mt-1 rounded-md">
        <span class="text-red-500">*</span> 
          <span class="font-semibold">Bulan Pelaksanaan</span>
          <select name="date" id="date" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
            <option value="">-- Pilih Bulan --</option>
            @foreach ($months as $monthNumber => $monthName)
            @php
                $targetColumn = 'target_unit_' . $monthNumber;
                $targetValue = $formatKgValue($target->$targetColumn);                
            @endphp
            <option value="{{ $monthNumber }}" data-target="{{ $targetValue ?? '' }}" data-unit="{{ $target->unit }}" data-rp="{{ $target->unit == 'Rp' || $target->unit == 'Rp/Kg' ? 'yes' : 'no' }}" data-kg="{{ $target->unit == 'Kg' ? 'yes' : 'no' }}" data-zero="{{ $target->{$targetColumn} == 0 ? 'yes' : 'no' }}" data-is-null="{{ $target->{$targetColumn} === null ? 'yes' : 'no' }}">
                {{ $monthName }}
            </option>
            @endforeach
        </select>
        @php
        // dd($targetValue);
        @endphp
        <div class="absolute inset-y-0 right-0 flex items-center">
        </div>
      </div>
       
        <div class="relative mt-1 rounded-md">
          <span class="pl-3 font-semibold">Nama</span>  
        <input type="text" name="name" id="name" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Nama" value="{{ $target->employee }}" readonly>
        <div class="absolute inset-y-0 right-0 flex items-center">
        </div>
      </div>
      <div class="relative mt-1 rounded-md">
        <span class="pl-3 font-semibold">Posisi</span>  
      <input type="text" name="occupation" id="occupation" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Posisi" value="{{ $target->occupation }}" readonly>
      <div class="absolute inset-y-0 right-0 flex items-center">
      </div>
    </div>
    <div id="div-month-target" class="relative mt-1 rounded-md hidden">
        <span class="text-red-500">*</span> 
          <span class="font-semibold">Bulan Target</span>
          <select name="date-target" id="date-target" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
            <option value="">-- Pilih Bulan --</option>
            @foreach ($months as $monthNumber => $monthName)
            @php
                $targetColumn = 'target_unit_' . ltrim($monthNumber, '0');
                $targetValue = $formatKgValue($target->$targetColumn); 
            @endphp
            <option value="{{ $monthNumber }}" data-target="{{ $targetValue ?? '' }}" data-unit="{{ $target->unit }}" data-rp="{{ $target->unit == 'Rp' || $target->unit == 'Rp/Kg' ? 'yes' : 'no' }}" data-kg="{{ $target->unit == 'Kg' ? 'yes' : 'no' }}" data-zero="{{ $target->{$targetColumn} == 0 ? 'yes' : 'no' }}" data-is-null="{{ $target->{$targetColumn} === null ? 'yes' : 'no' }}">
                {{ $monthName }}
            </option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center">
        </div>
      </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-2">
        <div class="relative mt-1 rounded-md">
            <span class="pl-3 font-semibold">No. KPI</span>  
          <input type="text" name="kpi_code" id="kpi_code" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="No. KPI" value="{{ $target->code }}" readonly>
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
      </div>
      
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 items-center gap-x-6 gap-y-2" >
            <div class="relative mt-1 rounded-md">
              <span class="pl-3 font-semibold">Item KPI</span>  
              <textarea name="kpi_item" id="kpi_item" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Item KPI" rows="2" readonly>{{ $target->indicator }}</textarea>
            <div class="absolute inset-y-0 right-0 flex items-center">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 items-center gap-x-2 gap-y-2" >
              <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Periode Review</span>  
              <input type="text" name="review_period" id="review_period" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Periode Review" value="{{ $target->period }}" readonly>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
              <span class="pl-3 font-semibold">Satuan</span>  
            <input type="text" name="kpi_unit" id="kpi_unit" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Satuan" value="{{ $target->unit }}" readonly>
            <div class="absolute inset-y-0 right-0 flex items-center">
            </div>
          </div>
          <div class="relative mt-1 rounded-md">
            <span class="pl-3 font-semibold">Bobot</span>  
          <input type="text" name="kpi_weighting" id="kpi_weighting" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Bobot" value="{{ $target->weighting }}" readonly>
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
          <div class="relative mt-1 rounded-md">
            <span class="pl-3 font-semibold">Trend</span>  
          <input type="text" name="trend" id="trend" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Bobot" value="{{ $target->trend }}" readonly>
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-x-5">
          <div class="relative mt-1 rounded-md">
            <span class="pl-3 font-semibold">Target</span> 
            
          <input type="text" name="target" id="target" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Target" value="" readonly >
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
        <div class="relative mt-1 rounded-md">
            <span class="text-red-500">*</span>  
            <span class="font-semibold">Aktual</span>
          <input type="text" name="actual" id="actual" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Aktual" oninput="calculateAchievement()" autocomplete="off">
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
        <div class="relative mt-1 rounded-md">
            <span class="pl-3 font-semibold">"%" Pencapaian</span>  
          <input type="text" name="achievement" id="achievement" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Pencapaian" readonly>
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
        </div>
    </div>
            <div class="relative mt-1 rounded-md">
              <span class="pl-3 font-semibold">Cara Menghitung</span>  
              <textarea type="text" name="kpi_calculation" id="calculation" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Cara Menghitung" rows="2" readonly>{{ $target->calculation }}</textarea>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 items-center gap-x-6 gap-y-2" >
              </div>
              <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Keterangan</span>  
                <textarea name="detail" id="detail" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Keterangan" rows="2" readonly>{{ $target->detail }}</textarea>
                <input type="hidden" name="employee_id" id="employee_id" value="{{ $target->employee_id }}">
                <input type="hidden" name="pv_employee_id" id="pv_employee_id" value="{{ $target->employee_id }}">
                <input type="hidden" name="year" id="year" value="{{ request()->query('year') }}">
                <input type="hidden" name="status" id="status" value="Filled">
                <input type="hidden" name="role" id="role" value="{{ $role }}">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
              <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Komentar</span>  
                <textarea name="comment" id="comment" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Komentar" rows="2"></textarea>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
          </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-2 ">
        <div class="relative mt-1 rounded-md">
            <span class="pl-3 font-semibold">Nama Rekaman Data Pendukung</span>  
            <textarea name="supporting_document" id="supporting_document" class="block w-96 rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Item KPI" rows="3" readonly>{{ $target->supporting_document }}</textarea>
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
        <div class="relative mt-1 rounded-md">
            <span class="mx-20 font-semibold">Upload Rekaman</span>  
          <input type="file" name="record_file" id="record_file" class="file:absolute file:right-0 file:top-7 file:rounded-md file:bg-blue-500 file:text-white file:border-none file:py-1.5 w-32 mx-20 rounded-md border-0 py-1.5 pl-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1">
          <div class="ml-20 text-xs mt-0.5">
              <span class="text-red-500">*</span>
              <span>Format: .pdf .jpeg</span>
          </div>
          
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-2 ">
        <div class="relative mt-1 rounded-md">
          <div class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900  sm:text-sm sm:leading-6 mt-2 flex gap-x-6">
            <div class="mb-2 mt-2">
                {{-- @if ($dateNow > 15 && $role == 'Inputer')
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-300 text-white rounded-md" disabled >Submit</button>
                @elseif ($dateNow > 15 && $role == '')
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-300 text-white rounded-md" disabled >Submit</button>
                @elseif ($dateNow > 20 && $role == 'Checker Div 1')
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-300 text-white rounded-md" disabled >Submit</button>
                @elseif ($dateNow > 20 && $role == 'Checker Div 2')
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-300 text-white rounded-md" disabled >Submit</button>
                @elseif ($dateNow > 25 && $role == 'Approver')
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-300 text-white rounded-md" disabled >Submit</button>
                @else 
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md">Submit</button>
                @endif --}}
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md">Submit</button>
              {{-- <button type="submit" id="submitBtn" class="px-4 py-2 text-white rounded-md">Submit</button> --}}
            </div>
            <div class="mb-2 mt-2">
              <button type="submit" id="previewBtn" class="bg-green-500 text-white py-2 px-4 rounded-md">Preview</button>
          </div>
          <div class="absolute inset-y-0 right-0 flex items-center">
          </div>
        </div>
      </div>
    </form>
      @endforeach


      <script>
        document.getElementById('submitBtn').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('achievementForm').action = "{{ route('actual.store') }}";
            document.getElementById('achievementForm').submit();
        });
    
        document.getElementById('previewBtn').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('achievementForm').action = "{{ route('preview.store') }}";
            document.getElementById('achievementForm').submit();
        });

        // determine the target value based on the selected field
        document.getElementById('date').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var dataIsNull = selectedOption.getAttribute('data-is-null');
            var divMonthTarget = document.getElementById('div-month-target');

            if (dataIsNull === 'yes') {
                divMonthTarget.classList.remove('hidden');
            } else {
                divMonthTarget.classList.add('hidden');
            }

            updateTargetField();
            calculateAchievement();
        });

        document.getElementById('date-target').addEventListener('change', function () {
            updateTargetField();
            calculateAchievement();
        });

        function updateTargetField() {
            const dateField = document.getElementById('date');
            const selectedOption = dateField.options[dateField.selectedIndex];
            const dataIsNull = selectedOption.getAttribute('data-is-null');
            let dateFieldToUse;

            if (dataIsNull === 'yes') {
                dateFieldToUse = document.getElementById('date-target');
            } else {
                dateFieldToUse = document.getElementById('date');
            }

            console.log(dateFieldToUse);
            

            const selectedOptionToUse = dateFieldToUse.options[dateFieldToUse.selectedIndex];
            const targetValue = selectedOptionToUse.getAttribute('data-target');
            const unitValue = selectedOptionToUse.getAttribute('data-unit');
            const targetField = document.getElementById('target');

            if (unitValue === '%') {
                targetField.value = (targetValue * 100).toFixed(2) + '%'; // Round to 1 decimal place
            } else {
                targetField.value = targetValue;
            }
        }
    
        function calculateAchievement() {
            const targetField = document.getElementById('target');
            const actualField = document.getElementById('actual');
            const achievementField = document.getElementById('achievement');

            // determine the target value based on the selected field (date or date-target)
            const dateField = document.getElementById('date');
            const selectedOption = dateField.options[dateField.selectedIndex];
            const dataIsNull = selectedOption.getAttribute('data-is-null');
            let dateFieldToUse;
            if (dataIsNull == 'no') {
                dateFieldToUse = document.getElementById('date');
            } else {
                dateFieldToUse = document.getElementById('date-target');
            }

            const selectedOptionToUse = dateFieldToUse.options[dateFieldToUse.selectedIndex];

            

            const zeroValue = selectedOptionToUse.getAttribute('data-zero');
            const unitValue = selectedOptionToUse.getAttribute('data-unit');
            const kgValue = selectedOptionToUse.getAttribute('data-kg');
                    


            let target = parseFloat(targetField.value.replace(/,/g, '').replace(/[^0-9.%]/g, ''));
            let actual = parseFloat(actualField.value.replace(/,/g, '').replace(/[^0-9.%]/g, ''));
            
            if (zeroValue === 'yes' && (unitValue == 'Freq' || unitValue == 'Freq "0"' || unitValue == 'freq')) {
                if (actual == 0) {
                    achievementField.value = '100%';
                } else if (actual == 1) {
                    achievementField.value = '75%';
                } else if (actual == 2) {
                    achievementField.value = '50%';
                } else if (actual == 3) {
                    achievementField.value = '25%';
                } else if (actual == 4) {
                    achievementField.value = '10%';
                } else {
                    achievementField.value = '0%';
                }
            } else if (target === 1) {
                if (actual === target) {
                    achievementField.value = '100%';
                } else if (actual == target + 1) {
                    achievementField.value = '105%';
                } else if (actual == target + 2) {
                    achievementField.value = '110%';
                } else if (actual == target + 3) {
                    achievementField.value = '115%';
                } else if (actual >= target + 4) {
                    achievementField.value = '120%';
                } else {
                    achievementField.value = '0%';
                }
            } else if (unitValue === 'Tgl') {
                if (target === 1) { // Target == 1
                    if (actual === target) {
                        achievementField.value = '100%';
                    } else if (actual === target + 1) {
                        achievementField.value = '75%';
                    } else if (actual === target + 2) {
                        achievementField.value = '50%';
                    } else {
                        achievementField.value = '0%';
                    }
                } else if (target === 2) { // target == 2
                    if (actual === target - 1) {
                      achievementField.value = '105%';
                    } else if (actual === target) {
                        achievementField.value = '100%';
                    } else if (actual === target + 1) {
                        achievementField.value = '90%';
                    } else if (actual === target + 2) {
                        achievementField.value = '80%';
                    } else {
                        achievementField.value = '0%';
                    }
                } else if (target === 3) { // Target == 3
                    if (actual === target - 2) {
                        achievementField.value = '110%';
                    } else if (actual === target - 1) {
                      achievementField.value = '105%';
                    } else if (actual === target) {
                        achievementField.value = '100%';
                    } else if (actual === target + 1) {
                        achievementField.value = '90%';
                    } else if (actual === target + 2) {
                        achievementField.value = '80%';
                    } else if (actual === target + 3) {
                        achievementField.value = '70%';
                    }
                    else {
                        achievementField.value = '0%';
                    }
                } else if (target === 4) { // Target == 4

                    if (actual === target - 3) {
                        achievementField.value = '110%';
                    } else if (actual === target - 2) {
                        achievementField.value = '110%';
                    } else if (actual === target - 1) {
                      achievementField.value = '105%';
                    } else if (actual === target) {
                        achievementField.value = '100%';
                    } else if (actual === target + 1) {
                        achievementField.value = '90%';
                    } else if (actual === target + 2) {
                        achievementField.value = '80%';
                    } else if (actual === target + 3) {
                        achievementField.value = '70%';
                    } else if (actual === target + 4) {
                        achievementField.value = '60%';
                    }
                    else {
                        achievementField.value = '0%';
                    }
                } else if (target === 5) { // Target == 5

                    if (actual === target - 4) {
                        achievementField.value = '110%';
                    } else if (actual === target - 3) {
                        achievementField.value = '110%';
                    } else if (actual === target - 2) {
                        achievementField.value = '110%';
                    } else if (actual === target - 1) {
                      achievementField.value = '105%';
                    } else if (actual === target) {
                        achievementField.value = '100%';
                    } else if (actual === target + 1) {
                        achievementField.value = '90%';
                    } else if (actual === target + 2) {
                        achievementField.value = '80%';
                    } else if (actual === target + 3) {
                        achievementField.value = '70%';
                    } else if (actual === target + 4) {
                        achievementField.value = '60%';
                    } else if (actual === target + 5) {
                        achievementField.value = '50%';
                    }
                    else {
                        achievementField.value = '0%';
                    }
                } else if (target === 6) { // Target == 6

                    if (actual === target - 5) {
                        achievementField.value = '110%';
                    } else if (actual === target - 4) {
                        achievementField.value = '110%';
                    } else if (actual === target - 3) {
                        achievementField.value = '110%';
                    } else if (actual === target - 2) {
                        achievementField.value = '110%';
                    } else if (actual === target - 1) {
                      achievementField.value = '105%';
                    } else if (actual === target) {
                        achievementField.value = '100%';
                    } else if (actual === target + 1) {
                        achievementField.value = '90%';
                    } else if (actual === target + 2) {
                        achievementField.value = '80%';
                    } else if (actual === target + 3) {
                        achievementField.value = '70%';
                    } else if (actual === target + 4) {
                        achievementField.value = '60%';
                    } else if (actual === target + 5) {
                        achievementField.value = '50%';
                    } else if (actual === target + 6) {
                        achievementField.value = '40%';
                    }
                    else {
                        achievementField.value = '0%';
                    }
                } 
            } else {
                if (!isNaN(target) && !isNaN(actual) && target !== 0) {
                    let achievement;
                    var trendValue = document.getElementById('trend').value; // Retrieve the trend value
                    if (unitValue === 'Rp') {
                        achievement = (target / actual) * 100;
                    } else if (trendValue === 'Negatif') {
                        achievement = (target / actual) * 100;
                    } else {
                        achievement = (actual / target) * 100;
                    }

                    if (achievement >= 150 && unitValue == '%') {
                        achievement = 150;
                    } else if (achievement >= 120 && (unitValue == 'Kg/Tap' || unitValue == 'Rp' || unitValue == 'Rp/Kg' || unitValue == 'Hari' || unitValue == 'Jam')) {
                        achievement = 120;
                    } else if (achievement >= 110 && (unitValue == 'Jlh' || unitValue == 'Freq')) {
                        achievement = 100;
                    }


                    achievementField.value = Math.round(achievement) + '%';
                } else {
                    achievementField.value = '';
                }
            }
            
        }

        document.getElementById('date').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var dataIsNull = selectedOption.getAttribute('data-is-null');
            var divMonthTarget = document.getElementById('div-month-target');

            if (dataIsNull == 'yes') {
                divMonthTarget.classList.remove('hidden');
            } else {
                divMonthTarget.classList.add('hidden');
            }

            if (dataIsNull == 'no') {
                document.getElementById('date').addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    var targetValue = selectedOption.getAttribute('data-target');
                    var unitValue = selectedOption.getAttribute('data-unit');
                    var targetField = document.getElementById('target');
                    var dataNull = selectedOption.getAttribute('data-is-null');

                    if (unitValue === '%') {
                        targetValue = (targetValue * 100).toFixed(2); // Round to 2 decimal places
                        targetField.value = targetValue + '%';
                    } else {
                        targetField.value = targetValue;
                    }

                    calculateAchievement();
                });
            } else {
                document.getElementById('date-target').addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    var targetValue = selectedOption.getAttribute('data-target');
                    var unitValue = selectedOption.getAttribute('data-unit');
                    var targetField = document.getElementById('target');
                    var dataNull = selectedOption.getAttribute('data-is-null');

                    if (unitValue === '%') {
                        targetValue = (targetValue * 100).toFixed(2); // Round to 2 decimal places
                        targetField.value = targetValue + '%';
                    } else {
                        targetField.value = targetValue;
                    }

                    calculateAchievement();
                });
            }
        });

        var actualInput = document.getElementById('actual');
        actualInput.addEventListener('input', function() {
            var selectedOption = document.getElementById('date').options[document.getElementById('date').selectedIndex];
            if (selectedOption.getAttribute('data-rp') === 'yes') {
                limitInputLength(this, true);
                this.value = formatCurrency(this.value);
            } else {
                limitInputLength(this, false);
            }
            calculateAchievement();
        });

        document.getElementById('target').addEventListener('input', function() {
            limitInputLength(this, false);
            calculateAchievement();
        });

        function limitInputLength(input, isCurrency) {
            if (isCurrency) {
                input.value = input.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters for currency
            } else {
                input.value = input.value.replace(/[^0-9%.]/g, ''); // Allow numeric and % characters
            }
            if (input.value.length > 5) {
                input.value = input.value.slice(0, 7);
            }
        }

        function formatCurrency(value) {
            value = value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
            const number = parseFloat(value);
            return number.toLocaleString('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 0 });
        }
    </script>

</x-app-layout>