<?php

namespace App\Livewire\Shared;

use App\Models\Schedule;
use App\Models\ScheduleConfig;
use Livewire\Component;

class ScheduleSettingsModal extends Component
{

    public $schedule;
    public $selectedTimeConfig;
    public $weekendSettings = [
        'show_saturday' => false,
        'show_sunday' => false
    ];
    public $timeConfigs;

    protected $rules = [
        'selectedTimeConfig' => 'required|exists:dp_schedule_configs,id',
        'weekendSettings.show_saturday' => 'boolean',
        'weekendSettings.show_sunday' => 'boolean',
    ];

    protected $messages = [
        'selectedTimeConfig.required' => 'Lütfen bir zaman konfigürasyonu seçin.',
        'selectedTimeConfig.exists' => 'Seçilen zaman konfigürasyonu geçerli değil.',
        'weekendSettings.show_saturday.boolean' => 'Cumartesi ayarı geçerli değil.',
        'weekendSettings.show_sunday.boolean' => 'Pazar ayarı geçerli değil.',
    ];

    public function mount($scheduleId = null)
    {
        $this->schedule = $scheduleId ? Schedule::findOrFail($scheduleId) : null;
        $this->timeConfigs = ScheduleConfig::orderBy('name')->get();
        if ($this->schedule) {
            $this->selectedTimeConfig = $this->schedule->schedule_config_id;
             $this->weekendSettings = [
                'show_saturday' => $this->schedule->show_saturday ?? false,
                'show_sunday' => $this->schedule->show_sunday ?? false
            ];
        }
    }

    public function saveSettings()
    {
        $this->validate();
         try {
            if ($this->schedule) {
                $this->schedule->update([
                    'schedule_config_id' => $this->selectedTimeConfig,
                    'show_saturday' => $this->weekendSettings['show_saturday'],
                    'show_sunday' => $this->weekendSettings['show_sunday'],
                    'updated_at' => now()
                ]);
             }
             session()->flash('success', 'Takvim ayarları başarıyla kaydedildi.');
             $this->dispatch('close-settings-modal');
             $this->dispatch('settings-updated');

        } catch (\Exception $e) {
            session()->flash('error', 'Ayarlar kaydedilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function resetSettings()
    {
        $this->selectedTimeConfig = null;
        $this->weekendSettings = [
            'show_saturday' => false,
            'show_sunday' => false
        ];

        $this->resetValidation();
    }


    public function render()
    {
        return view('livewire.shared.schedule-settings-modal');
    }
}
