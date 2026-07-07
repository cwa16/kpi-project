<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-4 overflow-x-auto p-2 bg-white border border-gray-100 shadow-md shadow-black/10 rounded-md">
        @php
            $currentYear = Carbon\Carbon::now()->year;
            $startYear = 2024;
            $endYear = $currentYear + 2;
            $role = auth()->user()->role;
        @endphp
        <div class="flex justify-between">
            <div class="">
                <span class="font-bold text-2xl">Contoh Form Data Pendukung</span>
            </div>
        </div>


        <div class="flex justify-center items-center gap-3 mt-4 mb-2">
            @php
                $isDept = request()->routeIs('masterSupportingDocumentDeptIndex');
                $isIndividu = request()->routeIs('masterSupportingDocumentIndex');
            @endphp

            <a href="{{ route('masterSupportingDocumentDeptIndex') }}"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
       {{ $isDept
           ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200'
           : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                Data Pendukung Dept.
            </a>

            <a href="{{ route('masterSupportingDocumentIndex') }}"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
       {{ $isIndividu
           ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200'
           : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                Data Pendukung Individu
            </a>
        </div>
    </div>
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearDropdown = document.getElementById('year');

        // Set the dropdown values from localStorage if they exist
        const savedYear = localStorage.getItem('selectedYear');
        if (savedYear) {
            yearDropdown.value = savedYear;
        }

        // Save the dropdown values to localStorage on change
        yearDropdown.addEventListener('change', function() {
            const year = this.value;
            localStorage.setItem('selectedYear', year);
            updateLinks();
        });


        function updateLinks() {
            const year = yearDropdown.value;
            const links = document.querySelectorAll('a[id^="employee-link-"]');
            links.forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('year', year);
                link.href = url.toString();
            });
        }

        // Initial update of links
        updateLinks();
    });
</script>
