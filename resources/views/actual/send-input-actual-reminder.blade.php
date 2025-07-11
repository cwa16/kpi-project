<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        <div class="p-0">
            <span class="font-bold text-2xl">Send Reminder Input KPI</span>
        </div>

        <div class="mt-2">
            <table class="w-full table-fixed">
                <tr>
                    <th class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Reminder Input {{ "(10-15)" }}
                    </th>
                    <th class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Reminder Check 1 {{ "(10-15)" }}
                    </th>
                    <th class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Reminder Check 2 {{ "(15-20)" }}
                    </th>
                    <th class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Reminder Mng Approval {{ "(20-25)" }}
                    </th>
                </tr>
                <tr>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        <form action="{{ route('actual.sendReminderInput') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 p-2  text-white rounded-md my-2">Kirim Reminder Input</button>
                        </form>
                    </td>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        <form action="{{ route('actual.sendReminderCheck1') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 p-2  text-white rounded-md my-2">Kirim Reminder Check 1</button>
                        </form>
                    </td>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        <form action="{{ route('actual.sendReminderCheck2') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 p-2  text-white rounded-md my-2">Kirim Reminder Check 2</button>
                        </form>
                    </td>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        <form action="{{ route('actual.sendReminderMngApproval') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 p-2  text-white rounded-md my-2">Kirim Reminder Mng Approval</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <div class="mt-2">
            <table class="w-full table-fixed">
                <tr>
                    <th style="width: 4%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        No
                    </th>
                    <th style="width: 12%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        NIK
                    </th>
                    <th style="width: 25%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Nama
                    </th>
                    <th style="width: 15%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Dept
                    </th>
                    <th style="width: 25%" class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Email
                    </th>
                    <th class="border-2 border-gray-400 text-[14px] tracking-wide font-medium text-white py-1 px-4 bg-blue-700">
                        Send Role
                    </th>
                </tr>
                @php
                    $i = 0;
                @endphp
                @foreach ($employees as $item)
                @php
                    $i++;
                @endphp
                <tr class="{{ $i % 2 == 0 ? 'bg-gray-100' : 'bg-blue-200' }}">
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                       {{ $i }}
                    </td>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        {{ $item->nik }}
                    </td>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        {{ $item->name }}
                    </td>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        {{ $item->department_name }}
                    </td>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        {{ $item->email }}
                    </td>
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide px-2 py-0 text-center">
                        {{ $item->role }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="shadow-lg shadow-black/15 mb-2 mt-3">
        <div class="flex w-full items-center justify-between border-t border-gray-200 bg-white px-10 py-3 rounded-md">
            <div class="flex flex-1 justify-between sm:hidden">
                {{ $employees->links() }}
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $employees->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $employees->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $employees->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        @if ($employees->onFirstPage())
                            <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-default">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $employees->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
    
                        @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                            @if ($page == $employees->currentPage())
                                <span aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $page }}</span>
                            @elseif ($page == 1 || $page == $employees->lastPage() || ($page >= $employees->currentPage() - 1 && $page <= $employees->currentPage() + 1))
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                            @elseif ($page == $employees->currentPage() - 2 || $page == $employees->currentPage() + 2)
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 cursor-default">...</span>
                            @endif
                        @endforeach
    
                        @if ($employees->hasMorePages())
                            <a href="{{ $employees->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
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
    </div>
</x-app-layout>