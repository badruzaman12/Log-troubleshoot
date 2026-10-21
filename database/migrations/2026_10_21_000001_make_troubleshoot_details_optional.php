<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('troubleshoots', function (Blueprint $table) {
            $table->text('gejala')->nullable()->change();
            $table->text('solusi')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('troubleshoots')->whereNull('gejala')->update(['gejala' => '']);
        DB::table('troubleshoots')->whereNull('solusi')->update(['solusi' => '']);

        Schema::table('troubleshoots', function (Blueprint $table) {
            $table->text('gejala')->nullable(false)->change();
            $table->text('solusi')->nullable(false)->change();
        });
    }
};