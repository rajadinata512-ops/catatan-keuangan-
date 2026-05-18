<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->decimal('pemasukan', 30, 2)->default(0)->change();
            $table->decimal('pengeluaran', 30, 2)->default(0)->change();
            $table->decimal('saldo', 30, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->integer('pemasukan')->default(0)->change();
            $table->integer('pengeluaran')->default(0)->change();
            $table->integer('saldo')->default(0)->change();
        });
    }
};