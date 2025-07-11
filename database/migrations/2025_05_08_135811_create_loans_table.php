<?php

use App\Enum\ConfirmationStatusLoanEnum;
use App\Enum\StatusLoanBookEnum;
use App\Enum\TimelineStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('loans', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('book_id')->constrained()->onDelete('cascade');
      $table->date('loan_date');
      $table->date('due_date');
      $table->date('return_date')->nullable();
      $table->enum('timeline_status', [
        TimelineStatusEnum::OVERDUE->value,
        TimelineStatusEnum::ONTIME->value,
        TimelineStatusEnum::PENDING->value,
        TimelineStatusEnum::REJECTED->value,
      ])->default(TimelineStatusEnum::PENDING->value);
      $table->enum('loan_status', [
        StatusLoanBookEnum::BORROWED->value,
        StatusLoanBookEnum::RETURNED->value,
        StatusLoanBookEnum::PENDING->value,
        StatusLoanBookEnum::REJECTED->value
      ])->default(StatusLoanBookEnum::PENDING->value);
      $table->enum('confirmation_status', [
        ConfirmationStatusLoanEnum::PENDING->value,
        ConfirmationStatusLoanEnum::REJECTED->value,
        ConfirmationStatusLoanEnum::APPROVED->value,
      ])->default(ConfirmationStatusLoanEnum::PENDING->value);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('loans');
  }
};
