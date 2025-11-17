<?php

use App\Http\Controllers\BookCardController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\LibraryCardController;
use App\Livewire\App\Book\DetailBook;
use App\Livewire\App\Cart\Cart;
use App\Livewire\App\User\Denda\Denda;
use App\Livewire\App\User\Denda\DendaDetail;
use App\Livewire\App\User\Donasi\CreateDonasi;
use App\Livewire\App\User\Donasi\Donasi;
use App\Livewire\App\User\Donasi\DonasiDetail;
use App\Livewire\App\User\Extension\PerpanjangPeminjaman;
use App\Livewire\App\User\Extension\PerpanjangPeminjamanDetail;
use App\Livewire\App\User\Peminjaman\Peminjaman;
use App\Livewire\App\User\Peminjaman\PeminjamanDetail;
use App\Livewire\App\User\Pengembalian\Pengembalian;
use App\Livewire\App\User\Pengembalian\PengembalianDetail;
use App\Livewire\App\User\Profile\Profile;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\LandingPage;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RequestResetPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyNotice;
use App\Livewire\Admin\ResponsiScanner;


// Landing page
Route::get('/', LandingPage::class)->name('index');

// Auth
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::post('/logout', function () {
  Auth::logout();
  session()->invalidate();
  session()->regenerateToken();

  return redirect()->route('login');
})->name('logout');

// Reset password
Route::get('/forgot-password', RequestResetPassword::class)->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
Route::get('/email/verify', VerifyNotice::class)->middleware('auth')->name('verification.notice');

Route::middleware(['auth', 'role:admin|siswa|petugas'])->group(function () {

  // Profile
  Route::get('/profile', Profile::class)->name('profile.index');;

  // Book
  Route::get('/book', \App\Livewire\App\Book\Book::class)->name('book.index');
  Route::get('/book/{id}', DetailBook::class)->middleware('verified')->name('book.detail');

  // Ebook
  Route::get('/buku/{book}/baca', [EbookController::class, 'show'])->middleware('verified')->name('ebook.show');

  // Cart
  Route::get('/cart', Cart::class)->name('cart.index');

  // Riwayat pengembalian
  Route::get('/pengembalian', Pengembalian::class)->name('pengembalian.index');
  Route::get('/pengembalian/{id}', PengembalianDetail::class)->name('pengembalian.detail');

  // Riwayat peminjaman
  Route::get('/peminjaman', Peminjaman::class)->name('peminjaman.index');
  Route::get('/peminjaman/{id}', PeminjamanDetail::class)->name('peminjaman.detail');

  // Riwayat donasi
  Route::get('/donasi/store', CreateDonasi::class)->name('donasi.store');
  Route::get('/donasi', Donasi::class)->name('donasi.index');
  Route::get('/donasi/{id}', DonasiDetail::class)->name('donasi.detail');

  // Riwayat denda
  Route::get('/denda', Denda::class)->name('denda.index');
  Route::get('/denda/{id}', DendaDetail::class)->name('denda.detail');

  // Riwayat extenion pinjaman
  Route::get('/perpanjang-peminjaman', PerpanjangPeminjaman::class)->name('perpanjang-peminjaman.index');
  Route::get('/perpanjang-peminjaman/{id}', PerpanjangPeminjamanDetail::class)->name('perpanjang-peminjaman.detail');
});

Route::middleware(['auth'])->group(function () {
  Route::get('/book/{book}/print-card', [BookCardController::class, 'printCard'])
    ->name('book.print-card');

  Route::get('/book/print-cards-bulk/{ids}', [BookCardController::class, 'printCardsBulk'])
    ->name('book.print-cards-bulk');

  Route::get('/book/{book}/generate-card', [BookCardController::class, 'generateCard'])
    ->name('book.generate-card');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
  Route::get('/library-card/print/{user}', [LibraryCardController::class, 'printSingle'])
    ->name('library-card.print-single');

  Route::get('/library-card/print-bulk', [LibraryCardController::class, 'printBulk'])
    ->name('library-card.print-bulk');

  // Scanner page
  Route::get('/responsi', ResponsiScanner::class)->name('responsi.scanner');
});

// Verify email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();

  return redirect(route('book.index'));
})->middleware(['auth', 'signed'])->name('verification.verify');
