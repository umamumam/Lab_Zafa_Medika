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
        Schema::create('nilai_normals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->nullable()->constrained('tests')->onDelete('cascade');
            $table->foreignId('detail_test_id')->nullable()->constrained('detail_tests')->onDelete('cascade');
            $table->enum('jenis_kelamin', ['Laki - Laki', 'Perempuan', 'Umum'])->default('Umum');
            $table->integer('usia_min')->nullable();
            $table->integer('usia_max')->nullable();
            // $table->string('satuan');
            $table->enum('type', ['Single', 'Range']);
            $table->decimal('min')->nullable();
            $table->decimal('max')->nullable();
            // $table->string('nilai_normal')->nullable();
            // $table->string('interpretasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_normals');
    }
};
