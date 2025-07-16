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
        Schema::create('hasil_labs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_test_id')->constrained('visit_tests')->onDelete('cascade');
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('detail_test_id')->nullable()->constrained('detail_tests')->onDelete('set null');
            $table->enum('flag', ['H', 'L', '*'])->nullable()->comment('H: High, L: Low, *: Abnormal');
            $table->string('hasil')->nullable();
            $table->enum('status', ['Belum Valid', 'Valid'])->default('Belum Valid');
            $table->foreignId('validator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->text('kesan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_labs');
    }
};
