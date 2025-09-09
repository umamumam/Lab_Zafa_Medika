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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('norm', 6)->unique();
            $table->string('nama', 100);
            $table->date('tgl_lahir');
            $table->enum('jenis_kelamin', ['Laki - Laki', 'Perempuan']);
            $table->enum('status_pasien', ['APS / UMUM', 'Asuransi', 'BPJS', 'Lupis', 'Medical Check Up', 'Prolanis', 'Rujukan Faskes', 'Rujukan Dokter', 'Lainnya'])->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('no_bpjs', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
