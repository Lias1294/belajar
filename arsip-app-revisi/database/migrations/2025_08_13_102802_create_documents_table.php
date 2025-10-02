<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Identitas dokumen
            $table->string('no_surat')->nullable();
            $table->string('nama_dokumen');
            $table->foreignId('document_type_id')
                  ->constrained('document_types')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Lokasi fisik
            $table->unsignedInteger('kotak')->nullable();

            // Waktu penerbitan
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->date('tanggal_penerbitan')->nullable();

            // File metadata
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type', 191)->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);

            // Uploaded by (string saja)
            $table->string('uploaded_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('nama_dokumen');
            $table->index(['document_type_id', 'kotak']);
            $table->index(['tahun', 'tanggal_penerbitan']);

            // Unik untuk no_surat per tahun
            $table->unique(['no_surat', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
