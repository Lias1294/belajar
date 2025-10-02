<?php

namespace App\Exports;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DocumentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected ?string $search;
    protected $type; // id atau nama
    protected $kotak;
    protected $tahun;

    public function __construct($search = null, $type = null, $kotak = null, $tahun = null)
    {
        $this->search = $search;
        $this->type   = $type;
        $this->kotak  = $kotak;
        $this->tahun  = $tahun;
    }

    public function query()
    {
        return Document::query()
            ->with('type')
            ->when($this->search, function (Builder $q) {
                $q->where(function ($qq) {
                    $qq->where('nama_dokumen', 'like', "%{$this->search}%")
                        ->orWhere('no_surat', 'like', "%{$this->search}%");
                });
            })
            ->when($this->type, function (Builder $q) {
                $type = $this->type;
                $q->whereHas('type', function ($t) use ($type) {
                    if (is_numeric($type)) $t->where('id', $type);
                    else $t->where('nama', $type);
                });
            })
            ->when($this->kotak, fn($q) => $q->where('kotak', $this->kotak))
            ->when($this->tahun, fn($q) => $q->where('tahun', $this->tahun))
            ->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Dokumen',
            'Jenis',
            'No. Surat',
            'Tahun',
            'Tanggal Penerbitan',
            'Kotak',
            'Di-upload oleh',
            'Nama File Asli',
            'Ukuran (KB)',
            'Tanggal Dibuat',
        ];
    }

    public function map($d): array
    {
        $tglTerbit = $d->tanggal_penerbitan
            ? $d->tanggal_penerbitan->format('d M Y')
            : '-';

        return [
            $d->id,
            $d->nama_dokumen,
            $d->type->nama ?? '-',
            $d->no_surat ?? '-',
            $d->tahun ?? '-',
            $tglTerbit,
            $d->kotak,
            $d->uploaded_by ?? '-',
            $d->original_name ?: basename($d->file_path),
            $d->size_bytes ? (int) round($d->size_bytes / 1024) : 0,
            optional($d->created_at)->format('d-m-Y H:i'),
        ];
    }
}
