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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('isbn')->nullable();
            $table->foreignId('publisher_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('stock')->default(0);
            $table->string('type')->nullable();
            $table->boolean('is_student_work')->default(false);
            $table->integer('publication_year')->nullable();
            $table->string('classification_code')->nullable();
            $table->string('rack_location')->nullable();
            $table->string('subject')->nullable();
            $table->text('abstract')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
