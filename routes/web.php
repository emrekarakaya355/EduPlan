<?php

use Illuminate\Support\Facades\Route;
use \App\Livewire\UbysAktar;

Route::view('/', 'welcome');
Route::view('dashboard', 'dashboard')
    ->name('dashboard')->middleware('auth');


Route::get('/ubys', UbysAktar::class)->middleware('auth')->name('ubys');
Route::get('/courses', \App\Livewire\Courses\CourseList::class)->middleware('auth')->name('course-list');
Route::get('/schedule', App\Livewire\Schedule\Chart::class)->middleware('auth')->name('schedule');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
