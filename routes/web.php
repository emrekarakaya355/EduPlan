<?php

use App\Livewire\Classrooms\CreateClassroom;
use App\Livewire\UbysAktar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


Route::get('dashboard', \App\Livewire\Pages\Dashboard\IndexApex::class)
    ->name('dashboard')->middleware('auth');
Route::get('/', \App\Livewire\Pages\Dashboard\IndexApex::class)
    ->name('dashboard')->middleware('auth');

Route::get('dashboard2', \App\Livewire\Pages\Dashboard\Index::class)
    ->name('dashboard2')->middleware('auth');

Route::get('/instructor/{instructor}/constraints-modal', function($instructorId) {
    return view('livewire.settings.instructor-constraints', compact('instructorId'));
})->name('instructor.constraints.modal');

Route::get('/ubys', UbysAktar::class)->middleware('auth')->name('ubys');
Route::get('/courses', \App\Livewire\Courses\CourseList::class)->middleware('auth')->name('course-list');
Route::get('/settings', \App\Livewire\Pages\Settings\Index::class)->middleware('auth')->name('settings');
Route::get('/schedule', \App\Livewire\Pages\Program\Index::class)->middleware('auth')->name('schedule');
Route::get('/instructors', \App\Livewire\Pages\Instructor\Index::class)->middleware('auth')->name('instructors');
Route::get('/reports', \App\Livewire\Pages\Reports\Index::class)->middleware('auth')->name('reports');

Route::get('/schedules', App\Livewire\Schedule\CompactChart::class)->middleware('auth')->name('schedules');

Route::get('/classroom/create', CreateClassroom::class)->middleware('auth')->name('classroom.create');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/profil-resmi/{sicil}', function ($sicil) {

    $hashedSicil = md5('bi' . $sicil . 'lim');
    $imageUrl = "https://sistem.nevsehir.edu.tr/bizdosyalar/profilresimler/{{$sicil}}.jpg";

    try {
        $response = Http::timeout(10)->get($imageUrl);

        if ($response->successful()) {
            return response($response->body())
                ->header('Content-Type', $response->header('Content-Type') ?: 'image/jpeg')
                ->header('Cache-Control', 'public, max-age=3600');
        } else {
            return response()->file(public_path('assets/images/default-avatar.png'));
        }
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        Log::error("Connection error while proxying image for sicil: {$sicil}, Error: {$e->getMessage()}");
        return response()->file(public_path('assets/images/default-avatar.png'));
    } catch (\Exception $e) {
        Log::error("General error while proxying image for sicil: {$sicil}, Error: {$e->getMessage()}");
        return response()->file(public_path('assets/images/default-avatar.png'));
    }
})->name('get.instructor.profile.image');


require __DIR__.'/auth.php';
