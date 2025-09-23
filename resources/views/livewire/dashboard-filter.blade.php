<div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg mb-6">
    <div class="flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
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
            
            <div>
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
        </div>
        
        <div class="flex gap-2">
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
    
    @if ($errors->any())
        <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>