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
        Schema::create('jasmani_mcus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_test_id')->constrained('visit_tests')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_pemeriksa_id')->nullable()->constrained('dokters')->onDelete('set null');
            $table->date('tanggal_pemeriksaan')->nullable();

            // Keluhan Saat Ini - Pemeriksaan Fisik
            $table->text('keluhan_saat_ini')->nullable();
            $table->decimal('berat_badan', 8, 2)->nullable();
            $table->decimal('tinggi_badan', 8, 2)->nullable();
            $table->string('bmi')->nullable();

            // Hidung
            $table->string('hidung')->nullable();

            // Mata
            $table->string('mata_tanpa_kacamata_kiri')->nullable();
            $table->string('mata_tanpa_kacamata_kanan')->nullable();
            $table->string('mata_dengan_kacamata_kiri')->nullable();
            $table->string('mata_dengan_kacamata_kanan')->nullable();
            $table->string('buta_warna')->nullable();
            $table->string('lapang_pandang')->nullable();

            // Telinga
            $table->string('liang_telinga_kiri')->nullable();
            $table->string('liang_telinga_kanan')->nullable();
            $table->string('gendang_telinga_kiri')->nullable();
            $table->string('gendang_telinga_kanan')->nullable();

            // Thorax
            $table->string('ritme_pernapasan')->nullable();
            $table->string('pergerakan_dada')->nullable();
            $table->string('suara_pernapasan')->nullable();

            // Kardiovaskular
            // $table->string('kardiovaskular')->nullable(); Judul
            $table->string('tekanan_darah')->nullable();
            $table->string('frekuensi_jantung')->nullable();
            $table->string('bunyi_jantung')->nullable();

            // Mulut
            // $table->string('mulut')->nullable(); Judul
            $table->string('gigi')->nullable();

            // Abdomen
            $table->string('peristaltik')->nullable();
            $table->string('abdominal_mass')->nullable();
            $table->string('bekas_operasi')->nullable();

            // Kesimpulan Medis
            $table->text('kesimpulan_medis')->nullable();
            $table->text('temuan')->nullable();
            $table->text('rekomendasi_dokter')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jasmani_mcus');
    }
};
