<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('name');
            $table->string('document_number')->nullable();
            $table->date('document_date');
            $table->text('description')->nullable();

            // Metadata file — file fisik TIDAK disimpan sebagai blob di DB.
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_mime');
            $table->unsignedBigInteger('file_size'); // dalam bytes
            $table->string('file_extension', 10);

            $table->enum('status', ['aktif', 'arsip'])->default('aktif')->index();
            // Visibilitas akses dokumen: publik (semua role termasuk siswa),
            // internal (staf sekolah saja, tidak untuk siswa), atau privat
            // (hanya pemilik dan admin).
            $table->enum('visibility', ['publik', 'internal', 'privat'])->default('internal')->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['name']);
            $table->index(['document_number']);
            $table->index(['document_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
