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
        Schema::create('penerimaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->integer('jumlah');
            $table->enum('status', ['Belum Klaim', 'Terklaim'])->default('Belum Klaim');
            $table->date('tgl_terima')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Kasir
            $table->foreignId('metodebyr_id')->nullable()->constrained('metodebyrs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaans');
    }
};
