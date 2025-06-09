<?php

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
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\LandingPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\App\User\Profile\Profile;

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

Route::middleware(['auth', 'role:admin|siswa|petugas'])->group(function () {

  // Profile
  Route::get('/profile', Profile::class)->name('profile.index');;

  // Book
  Route::get('/book', \App\Livewire\App\Book\Book::class)->name('book.index');
  Route::get('/book/{id}', DetailBook::class)->name('book.detail');

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
