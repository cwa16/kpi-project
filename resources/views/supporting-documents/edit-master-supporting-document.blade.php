<x-app-layout :title="$title ?? 'Edit Data Pendukung'" :desc="$desc ?? 'Edit dokumen KPI departemen'">
    <div class="ml-64 mt-8 p-6">
        <div class="max-w-6xl mx-auto bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Edit Data Pendukung</h2>
                    <p class="text-sm text-gray-500">Perbarui informasi dokumen untuk KPI departemen</p>
                </div>
                {{-- Bagian Tahun saya pertahankan jika memang masih dibutuhkan di halaman Edit --}}
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-600">Tahun:</label>
                    <select id="year"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                        @for ($y = 2024; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>
                                {{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="p-6 bg-gray-50/50">
                {{-- Sesuaikan route action ke route update kamu --}}
                <form action="{{ route('updateMasterSupportingDocument', $data->id) }}" method="post" enctype="multipart/form-data"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    @csrf
                    {{-- Tambahkan method PUT jika route kamu menggunakan PUT --}}
                    @method('PUT')

                    <div class="relative w-full max-w-sm">
                        <label class="block mb-1 text-xs font-semibold text-gray-600 uppercase">Departemen</label>

                        {{-- Data dept yang sudah ada di database, kita ubah jadi array PHP --}}
                        @php
                            $selectedDepts = is_array($data->dept) ? $data->dept : json_decode($data->dept, true) ?? [];
                        @endphp

                        <select name="dept[]" id="dept" multiple class="hidden">
                            @foreach ($departments as $groupName => $items)
                                @foreach ($items as $item)
                                    <option value="{{ $item['name'] }}"
                                        {{ in_array($item['name'], $selectedDepts) ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>

                        <div id="select-display"
                            class="bg-white border border-gray-300 rounded-lg px-3 py-2 cursor-pointer min-h-[42px] flex flex-wrap gap-1 items-center transition-all hover:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-500/20">
                            <span class="text-gray-400 text-sm">Pilih Dept...</span>
                        </div>

                        <div id="dropdown-menu"
                            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl hidden max-h-80 overflow-y-auto transform transition-all border-t-4 border-t-indigo-500">

                            @foreach ($departments as $groupName => $items)
                                <div class="px-3 py-2 bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em] border-b border-gray-100">
                                    {{ $groupName }}
                                </div>

                                @foreach ($items as $item)
                                    @php
                                        $isSelected = in_array($item['name'], $selectedDepts);
                                    @endphp
                                    <div class="p-3 border-b border-gray-50 last:border-0 hover:bg-indigo-50 cursor-pointer text-sm flex justify-between items-center group transition-colors {{ $isSelected ? 'bg-indigo-50 selected' : '' }}"
                                        data-value="{{ $item['name'] }}" data-label="{{ $item['name'] }}">

                                        <span class="text-gray-700 group-[.selected]:text-indigo-700 group-[.selected]:font-semibold">{{ $item['name'] }}</span>

                                        <div class="h-5 w-5 rounded border {{ $isSelected ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300' }} flex items-center justify-center group-[.selected]:bg-indigo-600 group-[.selected]:border-indigo-600 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3 w-3 text-white {{ $isSelected ? 'block' : 'hidden' }} group-[.selected]:block" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-600 uppercase">Nama KPI</label>
                        {{-- Tambahkan value --}}
                        <input type="text" name="nama_kpi" placeholder="Contoh: Produktivitas RSS"
                            value="{{ old('nama_kpi', $data->nama_kpi) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-600 uppercase">Nama Dokumen</label>
                        {{-- Tambahkan value --}}
                        <input type="text" name="nama_file" placeholder="Contoh: Laporan Bulanan"
                            value="{{ old('nama_file', $data->nama_file) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-600 uppercase">
                            File (Biarkan kosong jika tidak diubah)
                        </label>
                        <input type="file" name="url_file"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        {{-- Menampilkan info file saat ini jika ada --}}
                        @if($data->url_file)
                            <div class="mt-1 text-xs text-gray-500">File saat ini: <a href="{{ Storage::url($data->url_file) }}" target="_blank" class="text-blue-600 underline">Lihat File</a></div>
                        @endif
                    </div>

                    <div class="lg:col-span-1 flex gap-2">
                        <a href="{{ route('masterSupportingDocumentIndex') ?? '#' }}"
                            class="w-1/3 inline-flex justify-center items-center px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-bold rounded-lg transition-colors shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="w-2/3 inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Script untuk Tahun --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearDropdown = document.getElementById('year');
        const savedYear = localStorage.getItem('selectedYear');
        if (savedYear) {
            yearDropdown.value = savedYear;
        }
        yearDropdown.addEventListener('change', function() {
            localStorage.setItem('selectedYear', this.value);
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
        updateLinks();
    });
</script>

{{-- Script untuk Custom Dropdown (Telah disesuaikan untuk Edit) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const display = document.getElementById('select-display');
        const menu = document.getElementById('dropdown-menu');
        const realSelect = document.getElementById('dept');
        const options = menu.querySelectorAll('[data-value]');

        // 1. Fungsi Toggle Menu
        function toggleMenu(show = null) {
            if (show === null) {
                menu.classList.toggle('hidden');
            } else if (show) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        }

        display.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu();
        });

        // 2. Fungsi Ambil Label Ringkas
        function getShortLabel(label) {
            if (label.includes('Field')) return 'Field (A-F)';
            if (label.includes('Sub Div')) return label.replace('Sub Div', 'S.Div');
            return label;
        }

        // 3. Update Visual Box (di-refactor untuk kemudahan penggunaan di Edit)
        function updateDisplay() {
            const selectedOptions = Array.from(realSelect.selectedOptions);

            if (selectedOptions.length === 0) {
                display.innerHTML = '<span class="text-gray-400 text-sm">Pilih Dept...</span>';
                return;
            }

            display.innerHTML = '';
            selectedOptions.forEach(opt => {
                const menuOption = menu.querySelector(`[data-value="${opt.value}"]`);
                const label = menuOption ? menuOption.getAttribute('data-label') : opt.text;

                const tag = document.createElement('span');
                tag.className = 'bg-slate-800 text-white text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 shadow-sm';
                tag.innerHTML = `
                ${getShortLabel(label)}
                <button type="button" class="ml-1 hover:text-red-400 transition-colors" data-remove="${opt.value}">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                </button>
            `;
                display.appendChild(tag);
            });
        }

        // --- PENTING UNTUK EDIT: Panggil updateDisplay di awal untuk merender tag yang sudah terpilih dari DB ---
        updateDisplay();

        // 4. Klik Item di Dropdown
        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const val = this.getAttribute('data-value');
                const targetOption = realSelect.querySelector(`option[value="${val}"]`);

                // Toggle Status Selected
                targetOption.selected = !targetOption.selected;

                // --- UPDATE VISUAL ITEM DI DROPDOWN ---
                if (targetOption.selected) {
                    this.classList.add('bg-indigo-50', 'selected');
                    const checkbox = this.querySelector('.rounded');
                    checkbox.classList.add('bg-indigo-600', 'border-indigo-600');
                    checkbox.querySelector('svg').classList.remove('hidden');
                } else {
                    this.classList.remove('bg-indigo-50', 'selected');
                    const checkbox = this.querySelector('.rounded');
                    checkbox.classList.remove('bg-indigo-600', 'border-indigo-600');
                    checkbox.querySelector('svg').classList.add('hidden');
                }

                updateDisplay();
            });
        });

        // 5. Klik Tombol Hapus Tag
        display.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('[data-remove]');
            if (removeBtn) {
                e.stopPropagation();
                const val = removeBtn.getAttribute('data-remove');
                const targetOption = realSelect.querySelector(`option[value="${val}"]`);

                if(targetOption) targetOption.selected = false;

                const menuOption = menu.querySelector(`[data-value="${val}"]`);
                if (menuOption) {
                    menuOption.classList.remove('selected', 'bg-indigo-50');
                    const checkbox = menuOption.querySelector('.rounded');
                    checkbox.classList.remove('bg-indigo-600', 'border-indigo-600');
                    checkbox.querySelector('svg').classList.add('hidden');
                }

                updateDisplay();
            }
        });

        // 6. Close Click Outside
        document.addEventListener('click', (e) => {
            if (!display.contains(e.target) && !menu.contains(e.target)) {
                toggleMenu(false);
            }
        });
    });
</script>
