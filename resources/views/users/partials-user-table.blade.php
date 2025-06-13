@php
    $i = ($users->currentPage() - 1) * $users->perPage();
@endphp
<Table>
    @foreach ($users as $user)
        @php
            $i++;
        @endphp
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

</Table>
