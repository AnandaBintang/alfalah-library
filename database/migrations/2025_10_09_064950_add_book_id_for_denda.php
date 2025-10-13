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
    Schema::table('fines', function (Blueprint $table) {
      if (!Schema::hasColumn('fines', 'book_id')) {
        $table->unsignedBigInteger('book_id')->nullable()->after('loan_id');
        $table->foreign('book_id')->references('id')->on('books')->cascadeOnDelete();
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('fines', function (Blueprint $table) {
      if (Schema::hasColumn('fines', 'book_id')) {
        $table->dropForeign(['book_id']);
        $table->dropColumn('book_id');
      }
    });
  }
};
