<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Register;
use App\Livewire\LandingPage;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', LandingPage::class)->name('index');

// Auth
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::post('/logout', Logout::class)->name('logout');
