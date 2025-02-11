<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class CourseList extends Component
{
    use WithPagination; // Use WithPagination trait for pagination support

    // Bu bileşen filtre güncellemelerini dinleyecek.
    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public $program, $year, $semester;

    public function mount()
    {
        // Session'dan filtre değerlerini alıyoruz.
        $this->program = Session::get('program', '');
        $this->year = Session::get('year', ''); // 'date' burada yıl bilgisi olarak saklanıyor
        $this->semester = Session::get('semester', '');
    }

    public function applyFilters($filters)
    {
        $this->program = $filters['program'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];

        Session::put('program', $this->program);
        Session::put('year', $this->year);
        Session::put('semester', $this->semester);
    }

    public function render()
    {
        if (empty($this->program)) {
            $courses = collect(); // Boş bir koleksiyon döndürüyoruz
        } else {
            // Eğer program filtresi doluysa, dersleri filtrele.
            $query = Course_class::query();

            $query->where('program_id', $this->program);

            if ($this->year || $this->semester) {
                $query->whereHas('course', function ($q) {
                    if ($this->year) {
                        $q->where('year', $this->year);
                    }
                    if ($this->semester) {
                        $q->where('semester', $this->semester);
                    }
                });
            }

            // Use the paginate method directly inside the render method
            $courses = $query->paginate(30);
        }
        return view('livewire.courses.course-list', compact('courses'));
    }
}
