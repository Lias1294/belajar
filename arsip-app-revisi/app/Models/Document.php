<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_surat',
        'nama_dokumen',
        'document_type_id',
        'kotak',
        'tahun',
        'tanggal_penerbitan',
        'file_path',
        'original_name',
        'mime_type',
        'size_bytes',
        'uploaded_by',
    ];

    protected $casts = [
        'tanggal_penerbitan' => 'date', 
    ];

    /**
     * Relasi ke DocumentType
     */
    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
}
