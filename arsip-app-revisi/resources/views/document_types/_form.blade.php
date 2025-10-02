@csrf
<div class="space-y-4">
  <div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Nama Jenis Dokumen</label>
    <input type="text" name="nama"
           class="block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           value="{{ old('nama', $documentType->nama ?? '') }}" required>
  </div>

  <div class="flex items-center gap-3 pt-2">
    <button
      class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
      Simpan
    </button>
    <a href="{{ route('document-types.index') }}"
       class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
      Batal
    </a>
  </div>
</div>
