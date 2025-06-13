<x-app-layout :title="$title" :desc="$desc">
   <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md border-collapse">
  <div class="flex justify-start">
    <div class="px-2">
      <span class="font-medium text-gray-600 text-sm">Employee Detail</span>
    </div>
  </div>
  <table>
    <tr>
      <td class="px-2 py-0 font-semibold text-sm">NIK</td>
      <td class="text-sm">:</td>
      <td class="px-2 py-0 text-sm">{{ $employeeDetail->nik }}</td>
    </tr>
    <tr>
      <td class="px-2 py-0 font-semibold text-sm">Nama</td>
      <td class="text-sm">:</td>
      <td class="px-2 py-0 text-sm">{{ $employeeDetail->name }}</td>
    </tr>
    <tr>
      <td class="px-2 py-0 font-semibold text-sm">Jabatan</td>
      <td class="text-sm">:</td>
      <td class="px-2 py-0 text-sm">{{ $employeeDetail->occupation }}</td>
    </tr>
    <tr>
      <td class="px-2 py-0 font-semibold text-sm">Departemen</td>
      <td class="text-sm">:</td>
      <td class="px-2 py-0 text-sm">{{ $employeeDetail->department }}</td>
    </tr>
  </table>
  </div>
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md border-collapse">
      @php
      $semesterQuery = request()->query('semester');
      $yearQuery = request()->query('year');

      @endphp
        <form action="{{ route('target.storeTargetKpi') }}" method="POST">
            @csrf
            @method('POST')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-x-4 gap-y-0">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-x-4 gap-y-0">
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Kode KPI</span>  
                <input type="text" name="code" id="code" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Kode KPI" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">KPI</span>  
                <textarea name="indicator" id="indicator" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Item KPI" rows="2"></textarea>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
        </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Cara Menghitung</span>  
                <textarea name="calculation" id="calculation" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Cara Menghitung" rows="2"></textarea>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-0">
                <div class="relative mt-1 rounded-md">
                    <span class="pl-3 font-semibold">Unit</span>  
                    <input type="text" name="unit" id="unit" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Unit" value="">
                  <div class="absolute inset-y-0 right-0 flex items-center">
                  </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-3 font-semibold">Periode Review</span>  
                    <input type="text" name="period" id="period" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Periode Review" value="">
                  <div class="absolute inset-y-0 right-0 flex items-center">
                  </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-3 font-semibold">Bobot</span>  
                    <input type="text" name="weighting" id="weighting" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Bobot" value="">
                  <div class="absolute inset-y-0 right-0 flex items-center">
                  </div>
                </div>
                <div class="relative mt-1 rounded-md mb-1">
                    <span class="pl-3 font-semibold">Trend</span>  
                    <input type="text" name="trend" id="trend" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Trend" value="">
                  <div class="absolute inset-y-0 right-0 flex items-center">
                  </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-3 font-semibold">Status</span>  
                    <select name="is_active" id="is_active" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                      <option value="">-- Pilih Status --</option>
                      <option value="1">Aktif</option>
                      <option value="0">Tidak Aktif</option>
                  </select>
                  <div class="absolute inset-y-0 right-0 flex items-center">
                  </div>
                </div>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-x-4 gap-y-0">
              <div class="relative mt-1 rounded-md">
                  <span class="pl-3 font-semibold">Data Pendukung</span>  
                  <textarea name="supporting_document" id="supporting_document" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Data Pendukung" rows="2"></textarea>
                <div class="absolute inset-y-0 right-0 flex items-center">
                </div>
              </div>
              <div class="relative mt-1 rounded-md">
                  <span class="pl-3 font-semibold">Detail</span>  
                  <textarea name="detail" id="detail" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Detail" rows="2"></textarea>
                <div class="absolute inset-y-0 right-0 flex items-center">
                </div>
              </div>
              </div>
        </div>
        <span class=" font-bold text-[18px]">Target KPI</span>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-x-4 gap-y-0">
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Jan</span>  
                <input type="text" name="target_1" id="target_1" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Jan" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Feb</span>  
                <input type="text" name="target_2" id="target_2" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Feb" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Mar</span>  
                <input type="text" name="target_3" id="target_3" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Mar" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Apr</span>  
                <input type="text" name="target_4" id="target_4" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Apr" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">May</span>  
                <input type="text" name="target_5" id="target_5" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="May" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Jun</span>  
                <input type="text" name="target_6" id="target_6" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Jun" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Jul</span>  
                <input type="text" name="target_7" id="target_7" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Jul" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Aug</span>  
                <input type="text" name="target_8" id="target_8" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Aug" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Sep</span>  
                <input type="text" name="target_9" id="target_9" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Sep" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Oct</span>  
                <input type="text" name="target_10" id="target_10" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Oct" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Nov</span>  
                <input type="text" name="target_11" id="target_11" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Nov" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-3 font-semibold">Dec</span>  
                <input type="text" name="target_12" id="target_12" class="block w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Dec" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
              <input type="hidden" name="employee_id" id="employee_id" value="{{ $employeeID }}">
              <input type="hidden" name="year" id="year" value="{{ $yearQuery }}">
              <input type="hidden" name="semester" id="semester" value="{{ $semesterQuery }}">
            </div>
        </div>
        <div class="flex justify-center mt-4 gap-3">
            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md" onclick="history.back()">Cancel</button>
            <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md">Submit</button>
        </div>
    </form>
    </div>
</x-app-layout>