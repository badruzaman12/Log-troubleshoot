<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perhatikan nama tabelnya saya sesuaikan menjadi 'troubleshoots' agar sinkron dengan model
        Schema::create('troubleshoots', function (Blueprint $table) {
            $table->id();
            $table->string('judul_masalah');
            $table->string('kategori');
            $table->text('gejala');
            $table->text('solusi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('troubleshoots');
    }
};