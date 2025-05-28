<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class ScheduleSettingsModal extends Component
{

    public $showModal = false;
    public $scheduleId;
    public $showSaturday = false;
    public $showSunday = false;
    public $timeFormat = '24';
    public $startHour = '08:00';
    public $endHour = '17:00';
    public $schedules = [];

    protected $listeners = [
        'open-settings-modal' => 'openModal',
        'close-settings-modal' => 'closeModal'
    ];

    protected $rules = [
        'selectedScheduleId' => 'required|exists:schedules,id',
        'showSaturday' => 'boolean',
        'showSunday' => 'boolean',
        'timeFormat' => 'in:12,24',
        'startHour' => 'required|date_format:H:i',
        'endHour' => 'required|date_format:H:i|after:startHour'
    ];

    public function mount($scheduleId = null)
    {
        $this->scheduleId = $scheduleId;
        dd($this->scheduleId);
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $this->schedules = Schedule::select('id', 'year', 'semester', 'program_id')
            ->with('program:id,name')
            ->orderBy('year', 'desc')
            ->orderBy('semester')
            ->get();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveSettings()
    {
        $this->validate();

        // Ayarları parent component'e gönder
        $this->dispatch('settings-updated', [
            'scheduleId' => $this->selectedScheduleId,
            'showSaturday' => $this->showSaturday,
            'showSunday' => $this->showSunday,
            'timeFormat' => $this->timeFormat,
            'startHour' => $this->startHour,
            'endHour' => $this->endHour
        ]);

        $this->dispatch('show-confirm', [
            'message' => 'Ayarlar başarıyla güncellendi.',
            'type' => 'success'
        ]);

        $this->closeModal();
    }

    public function resetToDefaults()
    {
        $this->showSaturday = false;
        $this->showSunday = false;
        $this->timeFormat = '24';
        $this->startHour = '08:00';
        $this->endHour = '17:00';
    }

    public function render()
    {
        return view('livewire.shared.schedule-settings-modal');
    }
}
