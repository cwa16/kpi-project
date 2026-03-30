<x-app-layout :title="$title" :desc="$desc">
    <div class="ml-64 mt-8 p-6">
        <div class="max-w-6xl mx-auto bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Input Data Pendukung</h2>
                    <p class="text-sm text-gray-500">Tambahkan dokumen baru untuk KPI departemen</p>
                </div>
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
                <form action="{{ route('storeMasterSupportingDocument') }}" method="post" enctype="multipart/form-data"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    @csrf

                    <div class="relative w-full max-w-sm">
                        <label class="block mb-1 text-xs font-semibold text-gray-600 uppercase">Departemen</label>

                        <select name="dept[]" id="dept" multiple class="hidden">
                            @foreach ($departments as $groupName => $items)
                                @foreach ($items as $item)
                                    <option value="{{ $item['name'] }}">{{ $item['name'] }}</option>
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
                                <div
                                    class="px-3 py-2 bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em] border-b border-gray-100">
                                    {{ $groupName }}
                                </div>

                                @foreach ($items as $item)
                                    <div class="p-3 border-b border-gray-50 last:border-0 hover:bg-indigo-50 cursor-pointer text-sm flex justify-between items-center group transition-colors"
                                        data-value="{{ $item['name'] }}" data-label="{{ $item['name'] }}">

                                        <span
                                            class="text-gray-700 group-[.selected]:text-indigo-700 group-[.selected]:font-semibold">{{ $item['name'] }}</span>

                                        <div
                                            class="h-5 w-5 rounded border border-gray-300 flex items-center justify-center group-[.selected]:bg-indigo-600 group-[.selected]:border-indigo-600 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3 w-3 text-white hidden group-[.selected]:block" fill="none"
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
                        <input type="text" name="nama_kpi" placeholder="Contoh: Produktivitas RSS"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-600 uppercase">Nama Dokumen</label>
                        <input type="text" name="nama_file" placeholder="Contoh: Laporan Bulanan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-600 uppercase">File (PDF/Excel)</label>
                        <input type="file" name="url_file"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="lg:col-span-1">
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
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

        // 3. Update Visual Box
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
                tag.className =
                    'bg-slate-800 text-black text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 shadow-sm animate-in fade-in zoom-in duration-200';
                tag.innerHTML = `
                ${getShortLabel(label)}
                <button type="button" class="ml-1 hover:text-red-400 transition-colors" data-remove="${opt.value}">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                </button>
            `;
                display.appendChild(tag);
            });
        }

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
                    // Saat dipilih: BG Indigo, Teks Indigo, Checkbox Biru
                    this.classList.add('bg-indigo-50', 'selected');
                    const checkbox = this.querySelector('.rounded');
                    checkbox.classList.add('bg-indigo-600', 'border-indigo-600');
                    checkbox.querySelector('svg').classList.remove('hidden');
                } else {
                    // Saat batal dipilih: Balikkan ke normal
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
                targetOption.selected = false;

                const menuOption = menu.querySelector(`[data-value="${val}"]`);
                if (menuOption) menuOption.classList.remove('selected');

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
