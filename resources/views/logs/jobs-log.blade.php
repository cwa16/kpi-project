<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-60 mt-4 overflow-x-auto p-2 bg-gray-100 border border-gray-200 shadow-md shadow-black/10 rounded-md">
        <div class="mb-1">
            <p class=" font-bold text-xl">Reminder Input</p>
        </div>
        <div class="mb-2">
            <table class="w-full">
                <tr>
                    <th style="width: 1%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700">Queue Status</th>
                </tr>
                @php
                $i = 0;
                @endphp
                @foreach ($inputLines as $log)
                @php
                $i++;
                @endphp
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-700 py-1 px-2">
                        {{ $log }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="mb-1">
            <p class=" font-bold text-xl">Reminder Check 1</p>
        </div>
        <div class="mb-2">
            <table class="w-full">
                <tr>
                    <th style="width: 1%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700">Queue Status</th>
                </tr>
                @php
                $i = 0;
                @endphp
                @foreach ($check1Lines as $log)
                @php
                $i++;
                @endphp
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-700 py-1 px-2">
                        {{ $log }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="mb-1">
            <p class=" font-bold text-xl">Reminder Check 2</p>
        </div>
        <div class="mb-2">
            <table class="w-full">
                <tr>
                    <th style="width: 1%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700">Queue Status</th>
                </tr>
                @php
                $i = 0;
                @endphp
                @foreach ($check2Lines as $log)
                @php
                $i++;
                @endphp
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-700 py-1 px-2">
                        {{ $log }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="mb-1">
            <p class=" font-bold text-xl">Reminder Approve</p>
        </div>
        <div class="mb-2">
            <table class="w-full">
                <tr>
                    <th style="width: 1%;" class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-white py-0 bg-blue-700">Queue Status</th>
                </tr>
                @php
                $i = 0;
                @endphp
                @foreach ($approveLines as $log)
                @php
                $i++;
                @endphp
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-blue-100'}}">
                    <td class="border-2 border-gray-400 text-[12px] tracking-wide font-medium text-gray-700 py-1 px-2">
                        {{ $log }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-app-layout>