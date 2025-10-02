@php use Illuminate\Support\Str; @endphp
@csrf

{{-- Nama Dokumen --}}
<div>
  <label class="mb-1 block text-sm font-medium text-gray-700">Nama Dokumen</label>
  <input type="text" name="nama_dokumen"
    class="block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    value="{{ old('nama_dokumen', $document->nama_dokumen ?? '') }}" required>
</div>

{{-- No Surat + Tahun --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
  <div>
    <label class="mb-1 block text-sm font-medium text-gray-700">No. Surat</label>
    <input type="text" name="no_surat"
      class="block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
      value="{{ old('no_surat', $document->no_surat ?? '') }}">
    <p class="mt-1 text-xs text-gray-500">Unik per tahun (opsional).</p>
  </div>
  <div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Tahun</label>
    <input type="number" name="tahun" min="1900" max="{{ now()->year + 1 }}"
      class="block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
      value="{{ old('tahun', $document->tahun ?? '') }}">
  </div>
</div>

{{-- Jenis Dokumen (Relasi) + Kotak --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
  <div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Jenis Dokumen</label>
    <select name="document_type_id"
      class="block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
      required>
      <option value="">-- Pilih --</option>
      @foreach($types as $t)
      <option value="{{ $t->id }}"
        {{ (string)old('document_type_id', $document->document_type_id ?? '') === (string)$t->id ? 'selected' : '' }}>
        {{ $t->nama }}
      </option>
      @endforeach
    </select>
  </div>

  <div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Kotak</label>
    <input type="number" name="kotak" min="1"
      class="block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
      value="{{ old('kotak', $document->kotak ?? 1) }}" required>
  </div>
</div>

{{-- Tanggal Penerbitan + Uploaded By --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
  <div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Tanggal Penerbitan</label>
    <input type="date" name="tanggal_penerbitan"
      class="block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
      value="{{ old('tanggal_penerbitan', isset($document->tanggal_penerbitan) ? $document->tanggal_penerbitan->format('Y-m-d') : '') }}">
    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">Di-upload oleh</label>
      <input type="text" name="uploaded_by"
        class="block w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        value="{{ old('uploaded_by', $document->uploaded_by ?? '') }}">
    </div>
  </div>

  {{-- File --}}
  <div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Upload File (doc, docx, xls, xlsx, csv)</label>
    <input type="file" name="file"
      class="block w-full rounded-xl border-gray-300 bg-white shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-indigo-700 hover:file:bg-indigo-100 focus:border-indigo-500 focus:ring-indigo-500"
      accept=".doc,.docx,.xls,.xlsx,.csv" {{ isset($document) ? '' : 'required' }}>
    @isset($document)
    <p class="mt-2 text-sm text-gray-500">
      File saat ini:
      <a href="{{ route('documents.download', $document) }}" class="text-indigo-600 hover:underline">
        {{ $document->original_name ?? 'Download' }}
      </a>
    </p>
    @endisset
  </div>

  {{-- Actions --}}
  <div class="mt-4 flex items-center gap-3">
    <button
      class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
      Simpan
    </button>
    <a href="{{ route('documents.index') }}"
      class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
      Batal
    </a>
  </div>