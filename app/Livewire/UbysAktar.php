<?php

namespace App\Livewire;

use App\Models\Birim;
use App\Models\Bolum;
use App\Models\Course_class;
use Livewire\Component;
use App\Services\ApiService;


class UbysAktar extends Component
{
    public $birims;
    public $selectedBirim;   // Kullanıcının seçtiği birim ID'si
    public $selectedBolum;   // Kullanıcının seçtiği birim ID'si
    public $selectedProgram; // Kullanıcının seçtiği program ID'si

    public $orphanedBirims;
    public $orphanedBolums;

    public $courseClasses = []; // Kurs sınıflarını tutacak dizi


    private ApiService $ubysService;

    public function boot(ApiService $ubysService)
    {
        $this->ubysService = $ubysService;
    }
    public function updateSelectedBolum()
    {
        echo 1;
    }
    public function updatedSelectedProgram()
    {

        $this->updateCourseClasses();
    }

    public function updateCourseClasses()
    {
        if ($this->selectedProgram) {
            $this->courseClasses = Course_class::whereHas('program', function($query) {
                $query->where('id', $this->selectedProgram);
            })->get();

        } else {
            $this->courseClasses = [];
        }
    }

    public function mount()
    {
        $this->birims = Birim::whereHas('bolums.programs.courseClasses') // courseClasses olan programları getir
        ->orderBy('name')->get();
        $this->orphanedBirims = Birim::query()->whereDoesntHave('bolums')->get();
        $this->orphanedBolums = Bolum::query()->whereDoesntHave('programs')->get();
    }


    // Verileri çekmek için metod
    public function fetchData()
    {
        $this->ubysService->syncData(2024,'Summer');
    }

    public function render()
    {
        return view('livewire.ubys-aktar');
    }
}
