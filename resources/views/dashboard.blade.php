<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 px-2">
        @php
        $auth = auth()->user();
        // dd($auth);
            $currentYear = Carbon\Carbon::now()->year;
            $startYear = 2024;
            $endYear = $currentYear + 2;
            $role = auth()->user()->role;

            $currentMonth = Carbon\Carbon::now()->month;
            if ($currentMonth > 7) {
                $currentSemester = '2';
            } else {
                $currentSemester = '1';
            }


        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-gradient-to-bl from-[#3572EF] to-[#050C9C] rounded-md border border-gray-200 p-6 mt-2 shadow-md shadow-black/15">
                <div class="flex justify-between">
                    <div>
                        <i class="text-white ri-user-fill text-xl"></i>
                        <div class="text-white text-xl font-semibold mb-1">Manager</div>
                    </div>
                    <div class="grid lg:grid-cols-1 w-24">
                        <table>
                            <tr>
                                <td class="text-sm font-medium text-white" style="width: 50%">Total</td>
                                <td class="text-sm font-medium text-white">:</td>
                                <td class="text-sm font-medium text-white">{{ $manager ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-medium text-white">Aktual</td>
                                <td class="text-sm font-medium text-white">:</td>
                                <td class="text-sm font-medium text-white">{{ $actualManager ?? 0 }}</td>
                            </tr>
                        </table>
                </div>
                </div>
            </div>
            @php
            // dd($deptLists, $role);
            @endphp
            <div class="bg-gradient-to-bl from-[#3572EF] to-[#050C9C] rounded-md border border-gray-200 p-6 mt-2 shadow-md shadow-black/15">
                <div class="flex justify-between">
                    <div>
                        <i class="text-white ri-group-fill text-2xl"></i>
                        <div class="text-white text-xl font-semibold mb-1">Asst Manager</div>
                    </div>
                    <div class="grid lg:grid-cols-1 w-24">
                        <table>
                            <tr>
                                <td class="text-sm font-medium text-white" style="width: 50%">Total</td>
                                <td class="text-sm font-medium text-white">:</td>
                                <td class="text-sm font-medium text-white">{{ $asstMng ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-medium text-white">Aktual</td>
                                <td class="text-sm font-medium text-white">:</td>
                                <td class="text-sm font-medium text-white">{{ $actualAsstManager }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-bl from-[#3572EF] to-[#050C9C] rounded-md border border-gray-200 p-6 mt-2 shadow-md shadow-black/15">
                <div class="flex justify-between">
                    <div>
                        <i class="ri-team-fill text-white text-2xl"></i>
                        <div class="text-white text-xl font-semibold mb-1">Monthly</div>
                    </div>
                    <div class="grid lg:grid-cols-1 w-24">
                        <table>
                            <tr>
                                <td class="text-sm font-medium text-white" style="width: 50%">Total</td>
                                <td class="text-sm font-medium text-white">:</td>
                                <td class="text-sm font-medium text-white">{{ $monthly ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-medium text-white">Aktual</td>
                                <td class="text-sm font-medium text-white">:</td>
                                <td class="text-sm font-medium text-white">{{ $actualMonthly ?? 0 }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <div class="pl-2 bg-gray-100 mt-4 grid lg:grid-cols-2 border-gray-200 shadow-md shadow-black/10 rounded-md gap-4">
            <div class="p-1 bg-gray-200 mt-2 grid lg:grid-cols-2 border-gray-200 rounded-md gap-4">
                <div class="relative mt-1 rounded-md">
                    <span class="pl-3 font-semibold">Departemen</span>
                    <div class="pl-3 mb-3">
                        <select name="department" id="department" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach ($deptLists as $item)

                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                  <div class="absolute inset-y-0 right-0 flex items-center">
                  </div>
                  <div class="p-1 bg-gray-200 mt-2 grid lg:grid-cols-2 border-gray-200 rounded-md ">
                    <div class="relative mt-1 rounded-md">
                    <span class="pl-3 font-semibold">Semester</span>
                    <div class="pl-2 mb-3">
                        <select name="semester" id="semester" class=" col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            <option value="">-- Semester --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                  </div>
                  <div class="relative mt-1 rounded-md">
                    <span class="pl-3 font-semibold">Tahun</span>
                    <div class="pl-2 mb-3">
                        <select name="year" id="year" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            <option value="">-- Tahun --</option>
                            @for ($year = $startYear; $year <= $endYear; $year++ )
                            <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                  </div>
                  <div class="relative mt-0 pl-3 rounded-md">
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                  </div>
                  </div>
                </div>

                <div class="relative mt-1 rounded-md">
                    <span class="pl-0.5 font-semibold">Search</span>
                    <div class="p-0">
                        <input type="text" name="filterName" id="filterName" class="w-64 rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-0" placeholder="Masukkan Nama" value="" autocomplete="off">
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                  </div>

            </div>
            <div class="mt-2 bg-gray-200 rounded-md min-h-[200px] mx-2">
                <div class="flex justify-between">
                    <div class="p-2">
                        <span class="p-4 font-bold text-xl">Standar KPI & Panduan Aplikasi KPI</span>
                    </div>
                    @if (auth()->user()->role == 'Superadmin' ||  auth()->user()->role == 'Approver' )
                    <div class="p-2">
                        <a href="{{ route('requirement.create') }}">
                            <button class="bg-blue-500 rounded-md text-white p-1 font-medium">Upload</button>
                        </a>
                    </div>
                    @endif
                </div>
                <div class="flex justify-center mt-2">
                    <button id="viewDocumentBtn" class="text-white bg-blue-500 p-2 rounded-md">View Standar KPI</button>
                </div>
                <div class="flex justify-center mt-4">
                    <button id="viewTutorialBtn" class="text-white bg-blue-500 p-2 rounded-md">Panduan Aplikasi KPI</button>
                </div>
            </div>

            <div class="mb-2  bg-gray-200 rounded-md min-h-[200px]">
                <div class="px-2 py-1">
                    <span class="p-4 font-bold text-xl">Daftar Karyawan</span>
                </div>
                <div class="py-2 px-2 overflow-y-auto max-h-[240px]">
                    <table class="table-auto w-full">
                        <thead>
                        <tr>
                            <th style="width: 3%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                            <th style="width: 45%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Nama</th>
                            <th style="width: 20%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Posisi</th>
                            <th style="width: 15%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Dept</th>
                        </tr>
                    </thead>

                    <tbody id="employeeTableBody">

                    </tbody>
                    </table>
                </div>
            </div>

                <div class="mb-2  bg-gray-200 rounded-md min-h-[200px] mx-2">
                    <div class="px-2 py-2 flex justify-between">
                        <div class="">
                            <p class="p-0.5 font-bold text-xl">Notifikasi:</p>
                        </div>
                        <div class="flex justify-between gap-x-3">
                            @if (auth()->user()->role == 'Superadmin' || auth()->user()->role == 'Approver' )
                            <a href="{{ route('notification.create') }}">
                                <i class="ri-add-line bg-green-600 text-white text-base p-0.5 rounded-sm"></i>
                            </a>
                            @endif
                            <a href="{{ route('notification.index') }}">
                                <p class="text-blue-500 hover:underline">See All</p>
                            </a>
                        </div>
                    </div>

                    <ul role="list" class="divide-y divide-gray-300 px-3 border">
                        <li x-data="{ open: false }" class="flex flex-col py-0 bg-gray-100 hover:bg-gray-300 p-2 rounded-lg shadow-lg shadow-black/15 mb-4" @click="open = !open">
                            <div class="flex justify-between mb-1 items-center">
                                <div class="flex min-w-0 gap-x-4 ml-3">
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-base font-bold text-gray-900">{{ $notification->title ?? '' }}</p>
                                        {{-- <p class="mt-0.5 truncate text-sm text-gray-500">{{ $notification->content }}</p> --}}
                                    </div>
                                </div>
                                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                                    <p class="text-sm/6 text-gray-900">{{ $notification->input_by ?? '' }} / {{ $notification->input_dept ?? '' }}</p>
                                    <p class="mt-1 text-xs/5 text-gray-500">{{ Carbon\Carbon::parse($notification->created_at ?? '')->format('d M Y ') }} / {{ Carbon\Carbon::parse($notification->created_at ?? '')->format('H:i') }}</p>
                                </div>
                            </div>
                            <hr>
                            <div x-show="open" x-transition class="ml-3 my-1.5">
                                <p class="text-sm text-gray-700">{{ $notification->content ?? '' }}</p>
                            </div>
                        </li>
                        <li>
                            <table class="w-full">
                            <tr>
                                <td style="width: 55%">
                                    <p class="text-base font-bold text-gray-900">Approval Request For KPI Dept</p>
                                </td>
                                <td style="width: 4%">
                                    <p class="text-base font-bold text-gray-900">:</p>
                                </td>
                                <td style="width: 41%" class="">
                                    <button id="viewApprovalDeptList" type="button">
                                        <p class="text-base font-semibold text-blue-500 underline"> {{ $approveListDept }} Items</p>
                                    </button>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="width: 36%">
                                    <p class="text-base font-bold text-gray-900">Approval Request For KPI Individual</p>
                                </td>
                                <td style="width: 4%">
                                    <p class="text-base font-bold text-gray-900">:</p>
                                </td>
                                <td style="width: 52%" class="">
                                    <button id="viewApprovalList" type="button">
                                        <p class="text-base font-semibold text-blue-500 underline"> {{ $approveList }} Items</p>
                                    </button>
                                </td>
                            </tr>
                            </table>
                            {{-- <div class="flex justify-between items-center p-2">
                                <div class="flex flex-col gap-x-4">
                                    <div class="flex gap-x-2">
                                        <div class="">
                                            <p class="text-base font-bold text-gray-900">Approval Request: </p>
                                        </div>
                                        <div class="">
                                            <a href="#">
                                                <p class="text-base font-semibold text-blue-500 underline"> {{ $approveList }} Requests.</p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex gap-x-2">
                                        <div class="">
                                            <p class="text-base font-bold text-gray-900">Dept Approval Request: </p>
                                        </div>
                                        <div class="">
                                            <a href="#">
                                                <p class="text-base font-semibold text-blue-500 underline"> {{ $approveList }} Requests.</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </li>
                    </ul>
                </div>

            <!-- Modal -->
            <div id="documentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg p-4 w-1/2">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Standar KPI</h2>
                    <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="mt-4">
                    <object id="pdfObject" data="" type="application/pdf" width="100%" height="600px"></object>
                </div>
            </div>
        </div>

        <div id="tutorialModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg p-4 w-1/2">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Panduan Aplikasi KPI</h2>
                    <button id="closeModalTutorialBtn" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="mt-4">
                    <object id="pdfObjectTutorial" data="" type="application/pdf" width="100%" height="600px"></object>
                </div>
            </div>
        </div>


         <div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg p-4 w-[700px]">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">List Approval Employee</h2>
                    <button id="closeApprovalModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="mt-4 max-h-[500px] overflow-y-auto">
                    <table class="w-full">
                        <tr>
                            <th style="width: 3%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                            <th style="width: 70%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Dept</th>
                            <th style="width: 27%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Number of Requests</th>
                        </tr>
                        @php
                        $i = 0;
                        @endphp
                        @foreach ($approveListGroup as $item)
                        @php
                        $i++;
                        @endphp
                        <tr>
                            <td class="border border-gray-400 tracking-wide px-2 py-0 text-center">
                                {{ $i }}
                            </td>
                            <td class="border border-gray-400 tracking-wide px-2 py-0">
                                <a href="{{ route('report.index') . '?department=' . $item->department_id . '&role=' . $role }}" class="hover:underline hover:text-blue-500">
                                    {{ $item->department }}
                                </a>
                            </td>
                            <td class="border border-gray-400 tracking-wide px-2 py-0 text-center">
                                {{ $item->total }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <div id="approvalDeptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg p-4 w-1/2">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">List Approval Dept</h2>
                    <button id="closeApprovalDeptModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="mt-4">
                    <table class="w-full">
                        <tr>
                            <th style="width: 3%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">No.</th>
                            <th style="width: 70%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Dept</th>
                            <th style="width: 27%;" class="border border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">Number of Requests</th>
                        </tr>
                        @php
                        $i = 0;
                        @endphp
                        @foreach ($approveListDeptGroup as $item)
                        @php
                        $i++;
                        @endphp
                        <tr>
                            <td class="border border-gray-400 tracking-wide px-2 py-0 text-center">
                                {{ $i }}
                            </td>
                            <td class="border border-gray-400 tracking-wide px-2 py-0">
                                <a href="{{ route('report.department', $item->department_id) . '?semester=' . $currentSemester . '&year=' . $currentYear }}" class="hover:underline hover:text-blue-500">
                                    {{ $item->department }}
                                </a>
                            </td>
                            <td class="border border-gray-400 tracking-wide px-2 py-0 text-center">
                                {{ $item->total }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <!-- End Modal -->

        </div>

        </div>
        <div class="">
            <span class="text-gray-700 italic">Note: Klik pada kolom Departemen atau Nama untuk melihat detail</span>
        </div>
        <div class="">
            <span class="text-red-600 text-xl">*</span>
            <span class="text-gray-700 italic">Pilih semester dan tahun untuk melihat detail KPI Individu</span>
        </div>

    </div>


    <script src="https://unpkg.com/pdfobject"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
  const departmentElement = document.getElementById('department');
  const filterNameElement = document.getElementById('filterName');
  const yearElement = document.getElementById('year');
  const semesterElement = document.getElementById('semester');
  var reportShowUrl = "{{ route('report.show', ['id' => ':id']) }}";
  var reportShowDeptUrl = "{{ route('report.department', ['id' => ':id']) }}";


  function getReportUrl(id, semester, year) {
        return reportShowUrl.replace(':id', id) + '?semester=' + semester + '&year=' + year;
    }

    function getReportDeptUrl(department_id, semester, year){
        return reportShowDeptUrl.replace(':id', department_id) + '?semester=' + semester + '&year=' + year;
    }

  function fetchData() {
    const department = departmentElement.value;
    const name = filterNameElement.value;
    const year = yearElement.value;
    const semester = semesterElement.value;

    // Update the URL with the current filter state
    const url = new URL(window.location);
    url.searchParams.set('department', department);
    url.searchParams.set('name', name);
    url.searchParams.set('year', year);
    url.searchParams.set('semester', semester);
    history.pushState(null, '', url);

    // Fetch data based on the filters
    fetch(`{{ url('dashboard/filter') }}?department=${department}&name=${name}&semester=${semester}&year=${year}`)
      .then(response => response.json())
      .then(data => {
        const tbody = document.getElementById('employeeTableBody');
        tbody.innerHTML = '';

        if (data.length > 0) {
          data.forEach((item, index) => {
            const row = `<tr class="${index % 2 === 0 ? 'bg-blue-100' : 'bg-white'}">
              <td class="border border-gray-400 tracking-wide px-2 py-0 text-center">${index + 1}</td>
              <td class="border border-gray-400 tracking-wide px-2 py-0">
                <a href="${getReportUrl(item.id, semester, year)}" class="hover:underline">${item.name}</a>
              </td>
              <td class="border border-gray-400 tracking-wide px-2 py-0" >${item.occupation}</td>
              <td class="border border-gray-400 tracking-wide px-2 py-0">
               <a href="${getReportDeptUrl(item.department_id, semester, year)}" class="hover:underline">${item.department}</a>
              </td>
            </tr>`;
            tbody.innerHTML += row;
          });
        } else {
          tbody.innerHTML = '<tr><td colspan="4" class="border border-gray-400 tracking-wide px-2 py-0 text-center">No data found</td></tr>';
        }
      });
  }

  // Apply filters from the URL when the page loads
  const urlParams = new URLSearchParams(window.location.search);
  departmentElement.value = urlParams.get('department') || '';
  filterNameElement.value = urlParams.get('name') || '';
  yearElement.value = urlParams.get('year') || '';
  semesterElement.value = urlParams.get('semester') || '';

  fetchData();

  departmentElement.addEventListener('change', fetchData);
  filterNameElement.addEventListener('input', fetchData);
  yearElement.addEventListener('change', fetchData);
  semesterElement.addEventListener('change', fetchData);
});

    // Pdf object view standar dan panduan KPI
    document.getElementById('viewDocumentBtn').addEventListener('click', function() {
        document.getElementById('documentModal').classList.remove('hidden');

    });

    document.getElementById('viewTutorialBtn').addEventListener('click', function() {
        document.getElementById('tutorialModal').classList.remove('hidden');
    });

    document.getElementById('closeModalBtn').addEventListener('click', function() {
        document.getElementById('documentModal').classList.add('hidden');
    });

    document.getElementById('closeModalTutorialBtn').addEventListener('click', function() {
        document.getElementById('tutorialModal').classList.add('hidden');
    });

    // Close the modal when clicking outside of it
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('documentModal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('tutorialModal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

        document.getElementById('viewDocumentBtn').addEventListener('click', function() {
            fetch(`{{ route('requirement.index', 'status=Standard' ) }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const pdfUrl = data[0].file;
                        console.log(pdfUrl);


                        document.getElementById('pdfObject').setAttribute('data', `kpi_requirement_files/${pdfUrl}`);
                        document.getElementById('documentModal').classList.remove('hidden');
                    } else {
                        console.error('No PDF found');
                    }
                })
                .catch(error => console.error('Error fetching PDF URL:', error));

        });

        document.getElementById('viewTutorialBtn').addEventListener('click', function() {
            fetch(`{{ route('requirement.index', 'status=Tutorial') }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const pdfUrl = data[0].file;

                        document.getElementById('pdfObjectTutorial').setAttribute('data', `kpi_requirement_files/${pdfUrl}`);
                        document.getElementById('tutorialModal').classList.remove('hidden');
                    } else {
                        console.error('No PDF found');
                    }
                })
                .catch(error => console.error('Error fetching PDF URL:', error));
        });

        // Approval List
        document.getElementById('viewApprovalList').addEventListener('click', function() {
            document.getElementById('approvalModal').classList.remove('hidden');

        });

        document.getElementById('viewApprovalDeptList').addEventListener('click', function() {
            document.getElementById('approvalDeptModal').classList.remove('hidden');
        });

        document.getElementById('closeApprovalModal').addEventListener('click', function() {
            document.getElementById('approvalModal').classList.add('hidden');
        });

        document.getElementById('closeApprovalDeptModal').addEventListener('click', function() {
            document.getElementById('approvalDeptModal').classList.add('hidden');
        });

    </script>
</x-app-layout>
