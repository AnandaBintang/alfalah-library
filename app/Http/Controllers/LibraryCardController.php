<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryCardController extends Controller
{
  public function printSingle(User $user)
  {
    if (!$user->canPrintLibraryCard()) {
      abort(403, 'User tidak dapat mencetak kartu perpustakaan.');
    }

    $cardData = $user->getLibraryCardData();
    $barcodeImage = $this->generateBarcode($cardData['barcode_data']);

    $pdf = Pdf::loadView('library-card.single', [
      'user' => $user,
      'cardData' => $cardData,
      'barcodeImage' => $barcodeImage,
    ]);

    $pdf->setPaper('A4', 'portrait');

    return $pdf->stream("kartu-{$user->name}.pdf");
  }

  public function printBulk(Request $request)
  {
    $userIds = $request->input('user_ids', []);

    if (empty($userIds)) {
      return back()->withErrors(['message' => 'Tidak ada user yang dipilih.']);
    }

    $users = User::whereIn('id', $userIds)
      ->whereHas('roles', function ($q) {
        $q->where('name', 'siswa');
      })
      ->where('is_active', true)
      ->get();

    if ($users->isEmpty()) {
      return back()->withErrors(['message' => 'Tidak ada siswa aktif yang dipilih.']);
    }

    $cardsData = [];
    foreach ($users as $user) {
      $cardData = $user->getLibraryCardData();
      $barcodeImage = $this->generateBarcode($cardData['barcode_data']);

      $cardsData[] = [
        'user' => $user,
        'cardData' => $cardData,
        'barcodeImage' => $barcodeImage,
      ];
    }

    $pdf = Pdf::loadView('library-card.bulk', [
      'cardsData' => $cardsData,
    ]);

    $pdf->setPaper('A4', 'portrait');

    return $pdf->stream("kartu-perpustakaan-bulk.pdf");
  }

  private function generateBarcode(string $data): string
  {
    $generator = new BarcodeGeneratorPNG();
    $barcode = $generator->getBarcode($data, $generator::TYPE_CODE_128, 1.5, 40);

    return 'data:image/png;base64,' . base64_encode($barcode);
  }
}
