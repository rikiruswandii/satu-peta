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
            $table->id(); // ID utama
            $table->string('name'); // Nama file asli
            $table->string('path'); // Path file di penyimpanan
            $table->string('extension'); // Ekstensi file (e.g., pdf, jpg)
            $table->string('type'); // Ekstensi file (e.g., pdf, jpg)
            $table->bigInteger('size'); // Ukuran file dalam byte
            $table->string('mime_type'); // Tipe MIME file (e.g., application/pdf)
            $table->unsignedBigInteger('documentable_id'); // ID model pemilik dokumen
            $table->string('documentable_type'); // Nama model pemilik dokumen (e.g., User atau Project)
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->softDeletes(); // Kolom deleted

            $table->index(['documentable_id', 'documentable_type']);
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