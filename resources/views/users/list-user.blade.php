<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md border-collapse">
        @php
        $role = auth()->user()->role;
        @endphp
        <div class="flex justify-between">
            <div class="flex">
                <h1 class="text-2xl font-bold text-gray-800">Master Employee</h1>
            </div>
            <div class="p-0">
                <div class="flex justify-end">
                    <div class="relative mt-1 rounded-md">
                        <form action="{{ route('user.index') }}" method="GET">
                        <div class="mt-1 mb-1 mx-2">
                            <select name="department" id="department" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="">-- Departemen --</option>
            
                                <option value="all">All Dept</option>
                                @foreach ($deptList as $item)  
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center">
                        </div>
                    </div>
                    <div class="relative mt-1 rounded-md">
                        <form action="{{ route('target.department') }}" method="GET">
                        <div class="mt-1 mb-1 mx-2">
                            <select name="status" id="status" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="">-- Status --</option>
                                @foreach ($statusList as $item)
                                <option value="{{ $item->status }}">{{ $item->status }}</option>    
                                @endforeach
        
                            </select>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center">
                        </div>
                    </div>
                    <div class="mt-0 rounded-md mb-1">
                        <button type="submit" class="p-2 bg-blue-600 my-2 rounded-md text-white">
                            Filter
                        </button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-2 mb-2">
            <div class="">
                <input type="text" name="search" id="search" placeholder="Search" class="border border-gray-300 rounded-md px-4 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 mx-2" value="">
            </div>
            <div class="mb-0">
                <a href="{{ route('user.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600">Tambah Employee</a>
            </div>
        </div>
        <table class="w-full table-auto">
            <tr class="bg-blue-700">
                <th style="width: 3%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">No.</th>
                <th style="width: 8%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">NIK</th>
                <th style="width: 19%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">Nama</th>
                <th style="width: 10%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">Status</th>
                <th style="width: 6%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">Jabatan</th>
                <th style="width: 15%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">Email</th>
                <th style="width: 7%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">Input Type</th>
                <th style="width: 9%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">Role</th>
                <th style="width: 9%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">Status Aktif</th>
                <th style="width: 4%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-1">Aksi</th>
            </tr>
            @php
                $i = ($users->currentPage() - 1) * $users->perPage();
            @endphp
            <tbody id="user-table-body">
            @foreach ($users as $user)
            @php
                $i++;
            @endphp
                {{-- @include('users.partials-user-table') --}}
            <tr class="{{ $i % 2 == 0 ? 'bg-blue-100' : 'bg-white' }}">
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2 text-center">{{ $i }}</td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2">
                    {{ $user->nik }}
                </td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2">
                    {{ $user->name }}
                </td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2">
                    {{ $user->status }}
                </td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2">
                    {{ $user->occupation }}
                </td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2">
                    {{ $user->email }}
                </td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2">
                    {{ $user->input_type }}
                </td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2">
                    {{ $user->role }}
                </td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-1 px-2">
                    {{ $user->is_active }}
                </td>
                <td class="border-2 border-gray-400 text-[14px] tracking-wide py-0.5 px-1 text-center">
                   <div class="flex gap-x-1 justify-center">
                    <a href="{{ route('user.edit', $user->id) }}" class="bg-yellow-500 text-white px-1 py-0.5 rounded-md shadow-md hover:bg-yellow-600">
                        <i class="ri-edit-2-fill"></i>
                    </a>
                        <button type="submit" class="bg-red-500 text-white px-1 py-0.5 rounded-md shadow-md hover:bg-red-600" onclick="openModal('deleteModal-{{ $user->id }}')">
                            <i class="ri-delete-bin-6-line"></i>
                        </button>
                   </div>
                </td>
            </tr>

            {{-- delete modal --}}
            <div id="deleteModal-{{ $user->id }}" class="fixed flex inset-0 bg-gray-600 bg-opacity-50 items-center justify-center hidden">
                <div class="bg-white rounded-lg p-4 w-[450px]">
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('deleteModal-{{ $user->id }}')">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                    <div class="flex justify-center mb-5">
                        <h1 class="font-bold text-[20px] text-gray-800">Anda yakin menghapus data ini?</h1>
                    </div>
                    <div class="flex flex-col justify-center mb-5">
                        <div class="">
                            <p class="text-[14px] text-center text-gray-800">NIK: {{ $user->nik }}</p>
                        </div>
                        <div class="">
                            <p class="text-[14px] text-center text-gray-800">Nama: {{ $user->name }}</p>
                        </div>
                    </div>
                    <form action="{{ route('user.softDelete', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="flex justify-center gap-x-3">
                            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="closeModal('deleteModal-{{ $user->id }}')">
                                Batal
                            </button>
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md" >
                                Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- delete modal ends --}}
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="shadow-lg shadow-black/15 mb-2 mt-3 ml-64">
        <div class="flex w-full items-center justify-between border-t border-gray-200 bg-white px-10 py-3 rounded-md">
            <div class="flex flex-1 justify-between sm:hidden">
                {{ $users->links() }}
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $users->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $users->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $users->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        @if ($users->onFirstPage())
                            <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-default">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
    
                        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if ($page == $users->currentPage())
                                <span aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $page }}</span>
                            @elseif ($page == 1 || $page == $users->lastPage() || ($page >= $users->currentPage() - 1 && $page <= $users->currentPage() + 1))
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                            @elseif ($page == $users->currentPage() - 2 || $page == $users->currentPage() + 2)
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 cursor-default">...</span>
                            @endif
                        @endforeach
    
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @else
                            <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-default">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end of pagination --}}
</x-app-layout>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    document.getElementById('search').addEventListener('input', function() {
    let query = this.value;
    fetch(`{{ route('user.search') }}?search=${encodeURIComponent(query)}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('user-table-body').innerHTML = html;
        });
});
</script>