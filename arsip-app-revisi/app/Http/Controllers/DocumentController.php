<?php

namespace App\Http\Controllers;

use App\Exports\DocumentsExport;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');      // cari di nama_dokumen / no_surat
        $type   = $request->query('type');   // bisa ID atau nama type
        $kotak  = $request->query('kotak');  // nomor kotak
        $tahun  = $request->query('tahun');  // opsional

        $docs = Document::query()
            ->with('type')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('nama_dokumen', 'like', "%{$search}%")
                       ->orWhere('no_surat', 'like', "%{$search}%");
                });
            })
            ->when($type, function ($q) use ($type) {
                $q->whereHas('type', function ($t) use ($type) {
                    // kalau numeric, anggap ID; kalau string, cocokkan nama
                    if (is_numeric($type)) {
                        $t->where('id', $type);
                    } else {
                        $t->where('nama', $type);
                    }
                });
            })
            ->when($kotak, fn($q) => $q->where('kotak', $kotak))
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // untuk filter di form
        $types = DocumentType::orderBy('nama')->get();

        return view('documents.index', compact('docs', 'search', 'type', 'kotak', 'tahun', 'types'));
    }

    public function create()
    {
        $types = DocumentType::orderBy('nama')->get();
        return view('documents.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_surat'           => ['nullable', 'string', 'max:255',
                Rule::unique('documents')->where(fn($q) => $q->where('tahun', $request->input('tahun')))
            ],
            'nama_dokumen'       => 'required|string|max:255',
            'document_type_id'   => 'required|exists:document_types,id',
            'kotak'              => 'required|integer|min:1',
            'tahun'              => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'tanggal_penerbitan' => 'nullable|date',
            'uploaded_by'        => 'nullable|string|max:255',
            'file'               => [
                'required',
                'file',
                'max:10240',
                'mimes:doc,docx,xls,xlsx,csv',
                'mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,application/csv'
            ],
        ]);

        $uploaded = $request->file('file');
        $path = $uploaded->store('dokumen', 'public');

        Document::create([
            'no_surat'           => $data['no_surat'] ?? null,
            'nama_dokumen'       => $data['nama_dokumen'],
            'document_type_id'   => $data['document_type_id'],
            'kotak'              => $data['kotak'],
            'tahun'              => $data['tahun'] ?? null,
            'tanggal_penerbitan' => $data['tanggal_penerbitan'] ?? null,
            'uploaded_by'        => $data['uploaded_by'] ?? null,
            'file_path'          => $path,
            'original_name'      => $uploaded->getClientOriginalName(),
            'mime_type'          => $uploaded->getClientMimeType(),
            'size_bytes'         => $uploaded->getSize(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen tersimpan.');
    }

    public function edit(Document $document)
    {
        $types = DocumentType::orderBy('nama')->get();
        return view('documents.edit', compact('document', 'types'));
    }

    public function update(Request $request, Document $document)
    {
        $data = $request->validate([
            'no_surat'           => ['nullable', 'string', 'max:255',
                Rule::unique('documents')->ignore($document->id)->where(fn($q) => $q->where('tahun', $request->input('tahun')))
            ],
            'nama_dokumen'       => 'required|string|max:255',
            'document_type_id'   => 'required|exists:document_types,id',
            'kotak'              => 'required|integer|min:1',
            'tahun'              => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'tanggal_penerbitan' => 'nullable|date',
            'uploaded_by'        => 'nullable|string|max:255',
            'file'               => [
                'nullable',
                'file',
                'max:10240',
                'mimes:doc,docx,xls,xlsx,csv',
                'mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,application/csv'
            ],
        ]);

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $uploaded = $request->file('file');
            $document->file_path     = $uploaded->store('dokumen', 'public');
            $document->original_name = $uploaded->getClientOriginalName();
            $document->mime_type     = $uploaded->getClientMimeType();
            $document->size_bytes    = $uploaded->getSize();
        }

        $document->no_surat           = $data['no_surat'] ?? null;
        $document->nama_dokumen       = $data['nama_dokumen'];
        $document->document_type_id   = $data['document_type_id'];
        $document->kotak              = $data['kotak'];
        $document->tahun              = $data['tahun'] ?? null;
        $document->tanggal_penerbitan = $data['tanggal_penerbitan'] ?? null;
        $document->uploaded_by        = $data['uploaded_by'] ?? null;
        $document->save();

        return redirect()->route('documents.index')->with('success', 'Dokumen diperbarui.');
    }

    public function destroy(Document $document)
    {
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
        return back()->with('success', 'Dokumen dihapus.');
    }

    public function download(Document $document)
    {
        $path = Storage::disk('public')->path($document->file_path);
        return response()->download(
            $path,
            $document->original_name ?? basename($path),
            ['Content-Type' => $document->mime_type ?: 'application/octet-stream']
        );
    }

    public function exportExcel(Request $request)
    {
        $search = $request->query('q');
        $type   = $request->query('type');   
        $kotak  = $request->query('kotak');
        $tahun  = $request->query('tahun');

        $filename = 'laporan_dokumen_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new DocumentsExport($search, $type, $kotak, $tahun), $filename);
    }
}
