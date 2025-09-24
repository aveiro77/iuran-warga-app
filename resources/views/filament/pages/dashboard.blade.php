<x-filament-panels::page>
    <x-filament::section>
        <div class="p-6">
            {{-- Filter Section --}}
            <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Filter Periode</h3>
                
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
                {{-- Total Warga --}}
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

            {{-- Tabel Pemasukan Terbaru --}}
            <br>
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        <x-heroicon-o-banknotes class="h-5 w-5 inline mr-2 text-success-600" />
                        Transaksi Pemasukan Terbaru
                    </h3>
                    <a 
                        href="{{ url('/user/pemasukans') }}" 
                        class="text-primary-600 hover:text-primary-700 text-sm font-medium flex items-center"
                    >
                        Lihat Semua
                        <x-heroicon-o-arrow-right class="h-4 w-4 ml-1" />
                    </a>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No. Transaksi
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Warga
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Penarik
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($recentPemasukans as $pemasukan)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            <span class="font-mono text-xs bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                                {{ $pemasukan->nomor_transaksi }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $pemasukan->tanggal->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            <div class="font-medium">{{ $pemasukan->warga->nama }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                RT {{ $pemasukan->warga->rt }}/RW {{ $pemasukan->warga->rw }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-success-600 dark:text-success-400">
                                            Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $pemasukan->penarik }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <x-heroicon-o-document-magnifying-glass class="h-12 w-12 mx-auto text-gray-300 mb-2" />
                                            <p>Tidak ada data pemasukan untuk periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Hapus bagian hasMorePages() dan ganti dengan kondisi sederhana --}}
                    @if($recentPemasukans->count() >= 10)
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right">
                            <a 
                                href="{{ url('/user/pemasukans') }}" 
                                class="text-primary-600 hover:text-primary-700 text-sm font-medium"
                            >
                                Lihat lebih banyak...
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tabel Pengeluaran Terbaru --}}
            <br>
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        <x-heroicon-o-credit-card class="h-5 w-5 inline mr-2 text-danger-600" />
                        Transaksi Pengeluaran Terbaru
                    </h3>
                    <a href="{{ url('/user/pengeluarans') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium flex items-center"                    >
                        Lihat Semua
                        <x-heroicon-o-arrow-right class="h-4 w-4 ml-1" />
                    </a>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No. Transaksi
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kelompok
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Penanggung Jawab
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah Item
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($recentPengeluarans as $pengeluaran)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            <span class="font-mono text-xs bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                                {{ $pengeluaran->nomor_transaksi }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $pengeluaran->tanggal->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            <div class="font-medium">{{ $pengeluaran->kelompokPengeluaran->nama }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($pengeluaran->keterangan, 30) }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-danger-600 dark:text-danger-400">
                                            Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $pengeluaran->penanggung_jawab }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $pengeluaran->details_count }} item
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <x-heroicon-o-document-magnifying-glass class="h-12 w-12 mx-auto text-gray-300 mb-2" />
                                            <p>Tidak ada data pengeluaran untuk periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($recentPengeluarans->count() >= 10)
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right">
                            <a 
                                href="{{ url('/admin/pengeluarans') }}" 
                                class="text-primary-600 hover:text-primary-700 text-sm font-medium"
                            >
                                Lihat lebih banyak...
                            </a>
                        </div>
                    @endif
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