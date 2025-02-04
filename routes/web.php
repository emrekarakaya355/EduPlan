<?php

use Illuminate\Support\Facades\Route;
use \App\Livewire\UbysAktar;

Route::get('/', UbysAktar::class);
Route::view('dashboard', 'dashboard')
    ->name('dashboard');

Route::get('/ubys', UbysAktar::class);

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
