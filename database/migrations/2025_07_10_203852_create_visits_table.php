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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('no_order')->unique();
            $table->datetime('tgl_order');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sampling_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->enum('jenis_pasien', ['Umum', 'BPJS']);
            $table->foreignId('dokter_id')->nullable()->constrained('dokters')->onDelete('set null');
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangans')->onDelete('set null');
            $table->text('diagnosa')->nullable();
            $table->enum('jenis_order', ['Reguler', 'Cito'])->default('Reguler');
            $table->integer('total_tagihan');
            $table->integer('total_diskon')->default(0);
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->onDelete('set null');
            $table->foreignId('metodebyr_id')->nullable()->constrained('metodebyrs')->onDelete('set null');
            $table->integer('dibayar')->default(0);
            $table->enum('status_pembayaran', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            $table->enum('status_order', ['Sampling', 'Proses', 'Selesai'])->default('Sampling');
            $table->foreignId('paket_id')->nullable()->constrained('pakets')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
