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
                <span class="font-bold text-2xl">Edit Data Pendukung</span>
            </div>
        </div>

        <div class="flex justify-center mt-2 mb-2">
            <form action="{{ route('updateMasterSupportingDocument', ['id' => $data->id]) }}" method="post"
                enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                @method('PUT')
                <select name="dept" id="dept" class="border border-gray-300 rounded-md px-2 py-1 mr-2">
                    <option value="all">All</option>
                    @foreach ($dept as $item)
                        <option value="{{ $item->name }}" {{ $data->dept == $item->name ? 'selected' : '' }}>
                            {{ $item->name }}</option>
                    @endforeach
                </select>

                <input type="text" name="nama_kpi" id="nama_kpi" placeholder="Nama KPI"
                    value="{{ $data->nama_kpi }}" class="border border-gray-300 rounded-md px-2 py-1 mr-2">
                <input type="text" name="nama_file" id="nama_file" placeholder="Nama Data Pendukung" value="{{ $data->nama_file }}"
                    class="border border-gray-300 rounded-md px-2 py-1 mr-2">
                <input type="file" name="url_file" id="url_file"
                    class="border border-gray-300 rounded-md px-2 py-1 mr-2">
                <button type="submit" class="p-2 bg-green-600 text-white rounded-md">Update</button>
            </form>
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
