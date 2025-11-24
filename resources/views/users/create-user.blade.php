<x-app-layout :title="$title" :desc="$desc">
    <div
        class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md border-collapse">
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-3 gap-x-2">
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">NIK</span>
                    <select name="nik" id="nik-select"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1">
                        <option value="">Pilih NIK</option>
                        {{-- Loop data users untuk NIK --}}
                        @foreach ($usersEmp as $user)
                            <option value="{{ $user->nik }}">{{ $user->nik }} - {{ $user->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">Nama</span>
                    <input type="text" name="name" id="name"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1"
                        placeholder="Nama" value="" readonly>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">Status</span>
                    <input type="text" name="status" id="status"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1"
                        placeholder="Status" value="" readonly>

                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">Jabatan</span>
                    <input type="text" name="occupation" id="occupation"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1"
                        placeholder="Jabatan" value="" readonly>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">Grade</span>
                    <input type="text" name="grade" id="grade"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1"
                        placeholder="Grade" value="" readonly>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">Dept</span>
                    <input type="text" name="department_id" id="department_id"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1"
                        placeholder="Dept Name" value="" readonly>

                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-x-2 mt-1">
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">Email</span>
                    <input type="text" name="email" id="email"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1"
                        placeholder="Email" value="" readonly>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">Password</span>
                    <input type="password" name="password" id="password"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1"
                        placeholder="Password" value="">
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <span class="pl-1 font-semibold">No. HP</span>
                    <input type="text" name="nohp" id="nohp"
                        class="w-full rounded-md border-0 py-1.5 pl-4 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-1"
                        placeholder="NO. HP" value="" readonly>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                    </div>
                </div>
                <div class="relative mt-1 rounded-md">
                    <div class="">
                        <span class="pl-1 font-semibold">Input Type</span>
                    </div>
                    <div class="pl-1">
                        <input type="radio" name="input_type" id="input_type" value="Individual"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <label for="input_type" class="ml-2 text-sm font-medium text-gray-900">Individual</label>
                        <input type="radio" name="input_type" id="input_type" value="Group"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ml-4">
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
                            <input type="radio" name="role" id="role" value="Inputer"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                            <label for="role" class="ml-2 text-sm font-medium text-gray-900">Inputer</label>
                            <input type="radio" name="role" id="role" value="Checker 1"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ml-4">
                            <label for="role" class="mx-2 text-sm font-medium text-gray-900">Check 1</label>
                            <input type="radio" name="role" id="role" value="Checker 2"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                            <label for="role" class="ml-2 text-sm font-medium text-gray-900">Check 2</label>
                        </div>
                        <div class="">
                            <input type="radio" name="role" id="role" value="Mng Approver"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                            <label for="role" class="ml-2 text-sm font-medium text-gray-900">Mng Approver
                                {{ '(Mng)' }}</label>
                            <input type="radio" name="role" id="role" value="Approver"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ml-4">
                            <label for="role" class="ml-2 text-sm font-medium text-gray-900">Approver
                                {{ '(HRD)' }}</label>
                        </div>
                    </div>
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. INISIALISASI SELECT2
            $('#nik-select').select2({
                placeholder: "Cari berdasarkan NIK atau Nama", // Opsional: teks placeholder
                allowClear: true // Opsional: memungkinkan penghapusan pilihan
            });

            // 2. LOGIKA AJAX (Mengisi field otomatis saat NIK berubah)
            $('#nik-select').on('change', function() {
                const selectedNik = $(this).val();
                const url = '{{ route('get.user.data', ':nik') }}';
                const fetchUrl = url.replace(':nik', selectedNik);

                // Fungsi untuk mereset semua field otomatis
                const resetFields = () => {
                    $('#name').val('');
                    $('#status').val('');
                    $('#occupation').val('');
                    $('#grade').val('');
                    $('#department_id').val('').trigger('change'); // Reset select Department
                    $('#email').val('');
                    $('#nohp').val('');
                };

                if (!selectedNik) {
                    resetFields();
                    return;
                }

                // Ambil data menggunakan Fetch API
                fetch(fetchUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Isi semua input yang otomatis
                            $('#name').val(data.data.name || '');
                            $('#status').val(data.data.status || '');
                            $('#occupation').val(data.data.occupation || '');
                            $('#grade').val(data.data.grade || '');

                            // Isi Dept (menggunakan department_id) dan trigger change agar Select2/standard select terupdate
                            $('#department_id').val(data.data.department_id || '');

                            $('#email').val(data.data.email || '');
                            $('#nohp').val(data.data.no_hp || '');
                        } else {
                            alert('Data user tidak ditemukan.');
                            resetFields();
                        }
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                        alert('Terjadi kesalahan saat mengambil data.');
                        resetFields();
                    });
            });
        });
    </script>
</x-app-layout>
