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

  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-start justify-between gap-3">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Jenis Dokumen</h1>
        <p class="text-sm text-gray-500">Kelola daftar jenis dokumen untuk digunakan pada data dokumen.</p>
      </div>
      <a href="{{ route('document-types.create') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-white text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah
      </a>
    </div>

    @if ($errors->any())
      <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="GET" class="mb-5">
      <div class="relative max-w-md">
        <input type="text" name="q" value="{{ $q }}"
               class="w-full rounded-xl border-gray-300 bg-white pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
               placeholder="Cari jenis dokumen…">
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
          </svg>
        </div>
      </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              <th class="px-6 py-3 w-16">No</th>
              <th class="px-6 py-3">Nama</th>
              <th class="px-6 py-3 w-40">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($types as $i => $t)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-500">{{ $types->firstItem() + $i }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $t->nama }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <a href="{{ route('document-types.edit', $t) }}"
                       class="inline-flex items-center rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-600">
                      Edit
                    </a>
                    <form action="{{ route('document-types.destroy', $t) }}" method="POST"
                          onsubmit="return confirm('Hapus jenis dokumen ini?')">
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
                <td colspan="3" class="px-6 py-16">
                  <div class="text-center text-sm text-gray-600">Belum ada jenis dokumen.</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="border-t border-gray-100 px-4 py-3">
        {{ $types->onEachSide(1)->links() }}
      </div>
    </div>
  </div>
</x-app-layout>
