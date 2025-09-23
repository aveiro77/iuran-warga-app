<x-filament-panels::page>
    <x-filament::section>
        <div class="p-6">
            {{-- Filter Section --}}
            <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Filter Periode</h3>
                
                {{-- Modifikasi ini: Gunakan flexbox untuk tata letak yang lebih fleksibel --}}
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Dari Tanggal
                        </label>
                        <input
                            type="date"
                            id="startDate"
                            wire:model="startDate"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        >
                    </div>
                    
                    <div class="flex-1">
                        <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Sampai Tanggal
                        </label>
                        <input
                            type="date"
                            id="endDate"
                            wire:model="endDate"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        >
                    </div>
                    
                    {{-- Tambahkan flex-shrink-0 untuk tombol agar tidak menyusut --}}
                    <div class="flex gap-2 flex-shrink-0">
                        <button
                            wire:click="applyFilter"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                            Terapkan
                        </button>
                        
                        <button
                            wire:click="resetFilter"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            Reset
                        </button>
                    </div>
                </div>
                
                @error('startDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @error('endDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Periode Info --}}
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-950 rounded-lg">
                <p class="text-center text-blue-700 dark:text-blue-300">
                    <x-heroicon-o-information-circle class="h-5 w-5 inline mr-2" />
                    Menampilkan data dari <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> 
                    sampai <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                </p>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- ... (kode untuk cards) ... --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-primary-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-primary-100 dark:bg-primary-900 rounded-full mr-4">
                            <x-heroicon-o-user-group class="h-6 w-6 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Warga</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalWarga, 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Warga aktif</p>
                        </div>
                    </div>
                </div>

                {{-- Total Pemasukan --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-success-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-success-100 dark:bg-success-900 rounded-full mr-4">
                            <x-heroicon-o-banknotes class="h-6 w-6 text-success-600 dark:text-success-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pemasukan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total iuran warga</p>
                        </div>
                    </div>
                </div>

                {{-- Total Pengeluaran --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-danger-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-danger-100 dark:bg-danger-900 rounded-full mr-4">
                            <x-heroicon-o-credit-card class="h-6 w-6 text-danger-600 dark:text-danger-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengeluaran</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total pengeluaran kampung</p>
                        </div>
                    </div>
                </div>

                {{-- Saldo --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 {{ $saldo >= 0 ? 'border-success-500' : 'border-danger-500' }}">
                    <div class="flex items-center">
                        <div class="p-3 {{ $saldo >= 0 ? 'bg-success-100 dark:bg-success-900' : 'bg-danger-100 dark:bg-danger-900' }} rounded-full mr-4">
                            <x-heroicon-o-scale class="h-6 w-6 {{ $saldo >= 0 ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Saldo</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Saldo saat ini</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-primary-50 dark:bg-primary-950 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3 text-primary-800 dark:text-primary-200">Fitur yang Tersedia:</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-primary-600 mr-2" />
                            <span class="text-gray-700 dark:text-gray-300">Master Data Warga</span>
                        </li>
                        <li class="flex items-center">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-primary-600 mr-2" />
                            <span class="text-gray-700 dark:text-gray-300">Pencatatan Pemasukan Iuran</span>
                        </li>
                        <li class="flex items-center">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-primary-600 mr-2" />
                            <span class="text-gray-700 dark:text-gray-300">Pencatatan Pengeluaran Kampung</span>
                        </li>
                    </ul>
                </div>
                
                <div class="p-6 bg-success-50 dark:bg-success-950 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3 text-success-800 dark:text-success-200">Panduan Cepat:</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <span class="bg-success-100 text-success-800 rounded-full px-2 py-1 text-xs font-medium mr-2">1</span>
                            <span class="text-gray-700 dark:text-gray-300">Input data warga terlebih dahulu</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-success-100 text-success-800 rounded-full px-2 py-1 text-xs font-medium mr-2">2</span>
                            <span class="text-gray-700 dark:text-gray-300">Kemudian input pemasukan iuran</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-success-100 text-success-800 rounded-full px-2 py-1 text-xs font-medium mr-2">3</span>
                            <span class="text-gray-700 dark:text-gray-300">Terakhir input pengeluaran jika ada</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- JavaScript untuk update URL --}}
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('update-url', (data) => {
                const url = new URL(window.location);
                url.searchParams.set('start_date', data.start_date);
                url.searchParams.set('end_date', data.end_date);
                window.history.replaceState({}, '', url);
            });

            Livewire.on('reset-url', () => {
                const url = new URL(window.location);
                url.searchParams.delete('start_date');
                url.searchParams.delete('end_date');
                window.history.replaceState({}, '', url);
            });
        });
    </script>
</x-filament-panels::page>