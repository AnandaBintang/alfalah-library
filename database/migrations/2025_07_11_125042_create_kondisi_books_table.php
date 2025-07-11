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
        Schema::create('kondisi_books', function (Blueprint $table) {
          $table->id();
          $table->foreignId('book_id')->constrained()->cascadeOnDelete();
          $table->enum('status', ['RUSAK', 'HILANG'])->nullable();
          $table->text('notes')->nullable();
          $table->timestamp('reported_at')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_books');
    }
};
