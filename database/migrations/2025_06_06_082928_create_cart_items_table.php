<?php

use App\Enum\StatusCartItemEnum;
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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->enum('status', [
                StatusCartItemEnum::BOOKED->value,
                StatusCartItemEnum::APPROVED->value,
                StatusCartItemEnum::REJECTED->value,
            ])->default(StatusCartItemEnum::BOOKED->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
