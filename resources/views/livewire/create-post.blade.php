<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-900">Buat Postingan Baru</h1>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-red-800 font-semibold mb-2">Terjadi kesalahan:</h3>
                <ul class="text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form wire:submit.prevent="store" class="space-y-6">
            <!-- Judul -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Postingan
                </label>
                <input 
                    type="text"
                    id="title"
                    wire:model="title"
                    placeholder="Masukkan judul postingan..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                @error('title')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Komunitas (Opsional) -->
            <div>
                <label for="community_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Komunitas (Opsional)
                </label>
                <select 
                    id="community_id"
                    wire:model="community_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">-- Pilih Komunitas --</option>
                    @foreach ($communities as $community)
                        <option value="{{ $community->id }}">
                            {{ $community->name }}
                        </option>
                    @endforeach
                </select>
                @error('community_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Isi Postingan -->
            <div>
                <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                    Isi Postingan
                </label>
                <textarea 
                    id="body"
                    wire:model="body"
                    placeholder="Tulis postinganmu di sini..."
                    rows="8"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                ></textarea>
                @error('body')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="flex gap-4 pt-4">
                <button 
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Buat Postingan</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
                <a 
                    href="{{ route('home') }}"
                    class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
