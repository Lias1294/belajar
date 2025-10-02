<x-app-layout>

  @if(session('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '{{ session("success") }}',
    });
  </script>
  @endif

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex md:flex-row flex-col md:justify-between justify-center gap-2 mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Dokumen</h1>
        <p class="text-sm text-gray-500">Departemen Sistem Manajemen Terpadu & Inovasi</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('documents.create') }}"
          class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-white text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah
        </a>

        {{-- tombol export: bawa serta query filter --}}
        <a href="{{ route('documents.export.excel', request()->query()) }}"
          class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-green-600 px-4 py-2.5 text-sm font-medium text-gray-100 shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 16a1 1 0 0 0 .7-.29l4-4a1 1 0 1 0-1.4-1.42L13 12.59V4a1 1 0 1 0-2 0v8.59L8.7 10.29a1 1 0 0 0-1.4 1.42l4 4c.18.18.43.29.7.29Z" />
            <path d="M5 18a2 2 0 0 0 2 2h10a2 2 0 1 0 0-4H7a2 2 0 0 0-2 2Z" />
          </svg>
          Export Excel
        </a>
      </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="mb-6 space-y-3">
      {{-- Search --}}
      <div class="relative w-full max-w-sm">
        <input type="text" name="q" value="{{ $search }}"
          placeholder="Cari dokumen / no surat…"
          class="w-full rounded-xl border-gray-300 bg-white pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
          </svg>
        </span>
      </div>



      {{-- Filters --}}
      <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div>
          <select name="type"
            class="w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Jenis</option>
            @foreach($types as $t)
            <option value="{{ $t->id }}" {{ (string)$type===(string)$t->id ? 'selected' : '' }}>
              {{ $t->nama }}
            </option>
            @endforeach
          </select>
        </div>

        <div>
          <input type="number" name="kotak" value="{{ $kotak }}"
            class="w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Kotak (opsional)">
        </div>

        <div>
          <input type="number" name="tahun" value="{{ $tahun }}"
            class="w-full rounded-xl border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Tahun (opsional)">
        </div>

        <div class="flex gap-2">
          <button
            class="inline-flex w-full items-center justify-center rounded-xl border border-gray-300 bg-gray-700 px-4 py-2.5 text-sm font-medium text-gray-100 shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Filter
          </button>
          @if($search || $type || $kotak || $tahun)
          <a href="{{ route('documents.index') }}"
            class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-medium text-gray-100 bg-gray-500 hover:bg-gray-600 ">
            Reset
          </a>
          @endif
        </div>
      </div>
    </form>


    <!-- Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              <th class="px-6 py-3 w-16">No</th>
              <th class="px-6 py-3">Nama Dokumen</th>
              <th class="px-6 py-3">Jenis</th>
              <th class="px-6 py-3">No. Surat</th>
              <th class="px-6 py-3">Tahun</th>
              <th class="px-6 py-3">Kotak</th>
              <th class="px-6 py-3">File</th>
              <th class="px-6 py-3">Tanggal Penerbitan</th>
              <th class="px-6 py-3 w-44">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse ($docs as $i => $d)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ $docs->firstItem() + $i }}
              </td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ $d->nama_dokumen }}</div>
                @if($d->uploaded_by)
                <div class="text-xs text-gray-500">Uploader: {{ $d->uploaded_by }}</div>
                @endif
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">
                  {{ $d->type->nama ?? '-' }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ $d->no_surat ?? '-' }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ $d->tahun ?? '-' }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ $d->kotak }}</td>
              <td class="px-6 py-4">
                <div class="text-sm">
                  <a href="{{ route('documents.download',$d) }}"
                    class="text-indigo-600 hover:text-indigo-800 hover:underline">
                    {{ $d->original_name ? Str::limit($d->original_name, 34) : 'Download' }}
                  </a>
                  @if($d->size_bytes)
                  <div class="text-xs text-gray-500">{{ number_format($d->size_bytes/1024, 0) }} KB</div>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">
                {{ $d->tanggal_penerbitan?->format('d M Y') ?? '-' }}
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <a href="{{ route('documents.edit',$d) }}"
                    class="inline-flex items-center rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-600">
                    Edit
                  </a>
                  <form action="{{ route('documents.destroy',$d) }}" method="POST"
                    onsubmit="return confirm('Hapus dokumen ini?')">
                    @csrf @method('DELETE')
                    <button
                      class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="px-6 py-16">
                <div class="flex flex-col items-center justify-center text-center">
                  <div class="mb-3 rounded-full bg-gray-100 p-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M14.59 2.59a2 2 0 0 0-1.42-.59H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.83a2 2 0 0 0-.59-1.41l-4.82-4.83Z" />
                    </svg>
                  </div>
                  <p class="text-sm text-gray-600">Belum ada data dokumen.</p>
                  <a href="{{ route('documents.create') }}"
                    class="mt-3 inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                    Tambah Dokumen
                  </a>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="border-t border-gray-100 px-4 py-3">
        {{ $docs->onEachSide(1)->links() }}
      </div>
    </div>
  </div>
</x-app-layout>