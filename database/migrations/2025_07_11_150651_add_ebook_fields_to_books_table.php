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
    Schema::table('books', function (Blueprint $table) {
      $table->boolean('is_ebook')->default(false)->after('is_student_work');
      $table->enum('ebook_type', ['link', 'pdf'])->nullable()->after('is_ebook');
      $table->text('ebook_link')->nullable()->after('ebook_type');
      $table->string('ebook_file_path')->nullable()->after('ebook_link');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('books', function (Blueprint $table) {
      $table->dropColumn(['is_ebook', 'ebook_type', 'ebook_link', 'ebook_file_path']);
    });
  }
};
