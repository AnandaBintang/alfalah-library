<?php

namespace App\Livewire\App\User\Peminjaman;

use App\Enum\StatusLoanBookEnum;
use App\Models\Fine;
use App\Models\Loan as LoanModel;
use App\Models\LoanExtension;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Peminjaman')]
#[Layout('livewire.layouts.main-app')]
class PeminjamanDetail extends Component
{

  public $data;

  public function mount($id)
  {
    $this->data = LoanModel::where('user_id', Auth::id())
      ->where('id', $id)
      ->first();
  }

  public function requestExtension()
  {
    try {
      DB::beginTransaction();
      if (!$this->data) {
        LivewireAlert::title('Error!')
          ->text('Data peminjaman tidak ditemukan.')
          ->position('top-end')
          ->timer(5500)
          ->toast()
          ->error()
          ->show();
        DB::rollBack();
        return;
      }

      if ($this->data->loan_status !== StatusLoanBookEnum::BORROWED->value) {
        LivewireAlert::title('Error!')
          ->text('Peminjaman tidak bisa diperpanjang.')
          ->position('top-end')
          ->timer(5500)
          ->toast()
          ->error()
          ->show();
        DB::rollBack();

        return;
      }

      $existingExtension = LoanExtension::where('loan_id', $this->data->id)->first();
      if ($existingExtension) {
        LivewireAlert::title('Error!')
          ->text('Sudah pernah melakukan perpanjangan.')
          ->position('top-end')
          ->timer(5500)
          ->error()
          ->toast()
          ->show();

        DB::rollBack();
        return;
      }

      $now = Carbon::now();
      $dueDate = Carbon::parse($this->data->due_date);

      if ($now->gt($dueDate)) {
        $daysLate = $now->diffInDays($dueDate);
        $fineAmount = round($daysLate * 1000);

        Fine::create([
          'user_id' => Auth::id(),
          'loan_id' => $this->data->id,
          'amount' => $fineAmount,
          'status' => 'unpaid',
          'description' => "Denda keterlambatan $daysLate hari",
        ]);


        LivewireAlert::title('Perhatian!')
          ->text('Anda terlambat $daysLate hari, denda sebesar Rp $fineAmount telah ditambahkan.')
          ->position('top-end')
          ->timer(5500)
          ->info()
          ->show();
      }

      LoanExtension::create([
        'loan_id' => $this->data->id,
        'previous_due_date' => $this->data->due_date,
        'new_due_date' => $dueDate->addDays(7),
        'status' => 'pending',
      ]);

      LivewireAlert::title('Berhasil!')
        ->text('Permintaan perpanjangan telah dikirim.')
        ->position('center')
        ->timer(5500)
        ->success()
        ->show();

      $this->data = $this->data->fresh();
      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
    }

  }

  public function render()
  {
    return view('livewire.app.user.peminjaman.peminjaman-detail', ['data' => $this->data]);
  }
}
