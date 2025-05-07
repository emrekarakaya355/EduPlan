<?php

namespace App\Livewire\Reports;

use App\Enums\DayOfWeek;
use App\Models\Classroom;
use App\Models\ScheduleSlot;
use App\Traits\UsesScheduleDataFormatter;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
class ClassroomList extends Component
{
    use WithPagination, UsesScheduleDataFormatter;

    // Filtre değerleri
    public $filters = [
        'campus_id' => null,
        'building_id' => null,
        'classroom_type' => null,
        'min_capacity' => null,
        'max_capacity' => null,
        'is_active' => true,
        'show_available' => "",
    ];

    // Tablo seçenekleri
    public $perPage = 10;
    public $sortField = 'campus_name';
    public $sortDirection = 'asc';

    // Rapor veri depolama
    public $reportData = [];
    public $isReportGenerated = false;

    public $viewType = 'table';

    // Sayfa yükleme ve filtre dinleme
    public function mount()
    {

    }

    /**
     * Filtre event'i yakalandığında çalışır
     */
    #[On('filters-applied')]
    public function applyFilters($reportType, $filters)
    {
        $this->resetPage();
        $this->filters = $filters;
        $this->generateReport();
    }

    /**
     * Raporu oluştur
     */
    public function generateReport()
    {
        $this->reportData = [];
        $classrooms = $this->getFilteredClassrooms();
        $timeRanges = $this->generateTimeRange();

        foreach ($classrooms as $classroom) {
            $scheduleSlots = $classroom->scheduleSlots;
            foreach (DayOfWeek::cases() as $day) {
                foreach ($timeRanges as $time) {
                    $timeText = $time['start'] . ' - ' . $time['end'];

                    $hasSlot = $scheduleSlots->where('day', $day)->filter(function ($slot) use ($time) {
                        return $slot->start_time->format('H:i') == $time['start'];
                    })->isNotEmpty();

                    $isAvailable = !$hasSlot;

                    if (($this->filters['show_available'] && $isAvailable) ||
                        (!$this->filters['show_available'] && !$isAvailable)) {

                        $slotInfo = [];
                        if (!$isAvailable) {
                            $slotData = $scheduleSlots->where('day', $day)->filter(function ($slot) use ($time) {
                                return $slot->start_time->format('H:i') == $time['start'];
                            })->first();

                            $slotInfo = [
                                'class_name' => $slotData->courseClass->course?->name ?? '(Ders Bilgisi Yok)',
                                'class_code' => $slotData->courseClass->course?->code ?? '',
                                'instructor_name' => $slotData->courseClass->instructor
                                    ? $slotData->courseClass->instructorTitle . ' ' . $slotData->courseClass->instructor->name
                                    : '(Hoca Belirtilmemiş)',
                            ];
                        }

                        $this->reportData[] = [
                            'campus_name' => $classroom->building->campus->name ?? '(Yerleşke Belirtilmemiş)',
                            'building_name' => $classroom->building->name ?? '(Bina Belirtilmemiş)',
                            'classroom_name' => $classroom->name,
                            'classroom_type' => $classroom->type,
                            'capacity' => $classroom->class_capacity,
                            'day_name' => $this->getDayName($day->value),
                            'time_slot' => $timeText,
                            'is_available' => $isAvailable,
                            'slot_info' => $slotInfo,
                        ];
                    }
                }
            }
        }
        $this->sortReport();
        $this->isReportGenerated = true;
    }

    /**
     * Rapor verilerini sırala
     */
    public function sortReport()
    {
        // PHP'nin collection kullanımı için rapor verisini Collection'a çevir
        $collection = collect($this->reportData);

        // Sıralama yönüne göre sırala
        if ($this->sortDirection === 'asc') {
            $sorted = $collection->sortBy($this->sortField);
        } else {
            $sorted = $collection->sortByDesc($this->sortField);
        }

        // Sonucu tekrar diziye çevir
        $this->reportData = $sorted->values()->all();
    }

    /**
     * Tabloyu sıralama
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        if ($this->isReportGenerated) {
            $this->sortReport();
        }
    }

    /**
     * Filtrelenmiş derslikleri getir
     */
    public function getFilteredClassrooms()
    {
        $query = Classroom::query();

        if ($this->filters['campus_id']) {
            $query->whereHas('building', function ($query) {
                return $query->where('campus_id', $this->filters['campus_id']);
            });
        }

        if ($this->filters['building_id']) {
            $query->where('building_id', $this->filters['building_id']);
        }

        if ($this->filters['classroom_type']) {
            $query->where('type', $this->filters['classroom_type']);
        }

        if ($this->filters['min_capacity']) {
            $query->where('class_capacity', '>=', $this->filters['min_capacity']);
        }

        if ($this->filters['max_capacity']) {
            $query->where('class_capacity', '<=', $this->filters['max_capacity']);
        }

        if (!is_null($this->filters['is_active'])) {
            $query->where('is_active', $this->filters['is_active']);
        }

        return $query->with('building.campus','scheduleSlots')->paginate($this->perPage);
    }

    /**
     * Sınıf için ders programı slotlarını getir
     */
    public function getScheduleSlots($classroomId)
    {
        return ScheduleSlot::where('classroom_id', $classroomId)
            ->with(['courseClass.course', 'courseClass.instructor'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Gün numarasını gün adına çevir
     */
    public function getDayName($day)
    {
        $days = [
            1 => 'Pazartesi',
            2 => 'Salı',
            3 => 'Çarşamba',
            4 => 'Perşembe',
            5 => 'Cuma',
            6 => 'Cumartesi',
            7 => 'Pazar',
        ];

        return $days[$day] ?? $day;
    }

    /**
     * Sayfalanmış rapor verilerini döndür
     */
    public function getPaginatedReportDataProperty()
    {
        $page = $this->page ?? 1;
        $perPage = $this->perPage;
        $items = array_slice($this->reportData, ($page - 1) * $perPage, $perPage);

        return [
            'data' => $items,
            'links' => [
                'total' => count($this->reportData),
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil(count($this->reportData) / $perPage),
            ]
        ];
    }

    public function render()
    {
        $classrooms = $this->getFilteredClassrooms();
        return view('livewire.reports.classroom-list', [
            'classrooms' => $classrooms,
        ]);
    }
}
