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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->string('metode');
            $table->string('nilai_normal');
            // $table->enum('type', ['Single', 'Range']);
            // $table->decimal('min')->nullable();
            // $table->decimal('max')->nullable();
            $table->string('satuan');
            $table->integer('harga_umum')->default(0);
            $table->integer('harga_bpjs')->default(0);
            $table->enum('grup_test', ['Hematologi', 'Kimia Klinik', 'Imunologi / Serologi', 'Mikrobiologi', 'Khusus', 'Lainnya']);
            $table->enum('sub_grup', ['Cairan dan Parasitologi (E1)', 'Elektrometri (D1)', 'Endokrin Metabolik (B1)', 'Faal Ginjal (B3)', 'Faal Hati (B2)', 'Faal Hemotsasis (A2)', 'Faal Tiroid (B5)', 'Hematologi (A1)', 'Imunologi / Serologi (B4)', 'Marker Infeksi / Inflamasi (C1)', 'Marker Jantung (C2)', 'Lain - Lain (D2)']);
            $table->enum('jenis_sampel', ['Whole Blood EDTA', 'Whole Blood Heparin', 'Serum', 'Plasma Citrat', 'Urin', 'Feaces', 'Sputum', 'Cairan', 'LCS', 'Preparat', 'Swab']);
            $table->string('interpretasi')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
