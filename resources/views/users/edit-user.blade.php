<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md border-collapse">
        <form action="{{ route('user.update', $user->employee_id) }}" method="POST">
            @csrf
            @method('PUT')
        <div class="grid grid-cols-3 gap-x-2">
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">NIK</span>  
              <input type="text" name="nik" id="nik" class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="NIK" value="{{ $user->nik }}">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">Nama</span>  
              <input type="text" name="name" id="name" class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Nama" value="{{ $user->name }}">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">Status</span>  
                <select name="status" id="status" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    <option value="{{ $user->status }}">{{ $user->status }}</option>
                    @foreach ($statusList as $item)
                    <option value="{{ $item->status }}">{{ $item->status }}</option>    
                    @endforeach
                </select>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">Jabatan</span>  
              <input type="text" name="occupation" id="occupation" class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Jabatan" value="{{ $user->occupation }}">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">Grade</span>  
              <input type="text" name="grade" id="grade" class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Grade" value="{{ $user->grade }}">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">Dept</span>  
                <select name="department_id" id="department_id" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    <option value="{{ $user->department_id }}">{{ $user->department }}</option>
                    @foreach ($deptList as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>    
                    @endforeach
                </select>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
        </div>
        
        <div class="grid grid-cols-3 gap-x-2 mt-1">
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">Email</span>  
              <input type="text" name="email" id="email" class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Email" value="{{ $user->email }}">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">Password</span>  
              <input type="text" name="password" id="password" class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="Password" value="">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">No. HP</span>  
              <input type="text" name="phone" id="phone" class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1" placeholder="NO. HP" value="{{ $user->phone }}">
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
              <div class="">
                <span class="pl-1 font-semibold">Input Type</span>  
              </div>
              <div class="pl-1">
                <input type="radio" name="input_type" id="input_type" value="Individual" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                <label for="input_type" class="ml-2 text-sm font-medium text-gray-900">Individual</label>
                <input type="radio" name="input_type" id="input_type" value="Group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ml-4">
                <label for="input_type" class="ml-2 text-sm font-medium text-gray-900">Group</label>
              </div>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
              <div class="">
                <span class="pl-1 font-semibold">Role</span>  
              </div>
              <div class="pl-1">
                <div class="">
                  <input type="radio" name="role" id="role" value="Inputer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                  <label for="role" class="ml-2 text-sm font-medium text-gray-900">Inputer</label>
                  <input type="radio" name="role" id="role" value="Checker 1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ml-4">
                  <label for="role" class="mx-2 text-sm font-medium text-gray-900">Check 1</label>
                  <input type="radio" name="role" id="role" value="Checker 2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                  <label for="role" class="ml-2 text-sm font-medium text-gray-900">Check 2</label>
                </div>
                <div class="">
                  <input type="radio" name="role" id="role" value="Mng Approver" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                  <label for="role" class="ml-2 text-sm font-medium text-gray-900">Mng Approver {{ '(Mng)' }}</label>
                  <input type="radio" name="role" id="role" value="Approver" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ml-4">
                  <label for="role" class="ml-2 text-sm font-medium text-gray-900">Approver {{ '(HRD)' }}</label>
                </div>
              </div>
              <div class="absolute inset-y-0 right-0 flex items-center">
              </div>
            </div>
            <div class="relative mt-1 rounded-md">
                <span class="pl-1 font-semibold">Status Aktif</span>
                <select name="is_active" id="is_active" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                <option value="{{ $user->is_active }}">-- Status Aktif --</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center">
                </div>
              </div>
            </div>
            <div class="flex mt-5 gap-x-3 justify-center">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md" onclick="history.back();">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Submit</button>
            </div>
        </form>
    </div>
</x-app-layout>