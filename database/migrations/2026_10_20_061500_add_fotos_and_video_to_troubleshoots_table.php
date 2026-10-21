<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('troubleshoots', function (Blueprint $table) {
            $table->json('fotos')->nullable()->after('foto');
            $table->string('video')->nullable()->after('fotos');
        });
    }

    public function down(): void
    {
        Schema::table('troubleshoots', function (Blueprint $table) {
            $table->dropColumn(['fotos', 'video']);
        });
    }
};