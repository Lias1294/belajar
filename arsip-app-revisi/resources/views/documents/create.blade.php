<x-app-layout>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Tambah Dokumen</h1>
      <p class="text-sm text-gray-500">Unggah file Word/Excel dan lengkapi informasi dokumen.</p>
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

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="p-6">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
          @include('documents._form', ['types' => $types])
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
