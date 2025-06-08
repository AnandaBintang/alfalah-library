<?php

namespace App\Livewire\App\User\Peminjaman;

use App\Enum\StatusLoanBookEnum;
use App\Models\Fine;
use App\Models\Loan as LoanModel;
use App\Models\LoanExtension;
use App\Trait\NotificationsAndDialog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Peminjaman')]
#[Layout('livewire.layouts.main-app')]
class PeminjamanDetail extends Component
{
    use NotificationsAndDialog;

    public $data;

    public function mount($id)
    {
        $this->data = LoanModel::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();
    }

    public function requestExtension()
    {
        if (! $this->data) {
            $this->errorNotification('Error', 'Data peminjaman tidak ditemukan.');

            return;
        }

        if ($this->data->loan_status !== StatusLoanBookEnum::BORROWED->value) {
            $this->errorNotification('Error', 'Peminjaman tidak bisa diperpanjang.');

            return;
        }

        $existingExtension = LoanExtension::where('loan_id', $this->data->id)->first();
        if ($existingExtension) {
            $this->errorNotification('Error', 'Sudah pernah melakukan perpanjangan.');

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

            $this->infoNotification('Perhatian', "Anda terlambat $daysLate hari, denda sebesar Rp $fineAmount telah ditambahkan.");
        }

        LoanExtension::create([
            'loan_id' => $this->data->id,
            'previous_due_date' => $this->data->due_date,
            'new_due_date' => $dueDate->addDays(7),
            'status' => 'pending',
        ]);

        $this->successNotification('Berhasil', 'Permintaan perpanjangan telah dikirim.');

        $this->data = $this->data->fresh();
    }

    public function render()
    {
        return view('livewire.app.user.peminjaman.peminjaman-detail');
    }
}
