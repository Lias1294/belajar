<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $types = DocumentType::query()
            ->when($q, fn($query) => $query->where('nama', 'like', "%{$q}%"))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('document_types.index', compact('types', 'q'));
    }

    public function create()
    {
        return view('document_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:document_types,nama',
        ]);

        DocumentType::create($data);

        return redirect()->route('document-types.index')->with('success', 'Jenis dokumen dibuat.');
    }

    public function edit(DocumentType $documentType)
    {
        return view('document_types.edit', compact('documentType'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:document_types,nama,' . $documentType->id,
        ]);

        $documentType->update($data);

        return redirect()->route('document-types.index')->with('success', 'Jenis dokumen diperbarui.');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return back()->with('success', 'Jenis dokumen dihapus.');
    }
}
