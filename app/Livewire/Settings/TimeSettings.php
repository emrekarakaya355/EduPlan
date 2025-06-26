<?php

namespace App\Livewire\Settings;

use App\Models\ScheduleConfig;
use Livewire\Component;
use Livewire\Attributes\Validate;

class TimeSettings extends Component
{
    public $scheduleConfigs = [];
    public $showForm = false;
    public $editingId = null;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|date_format:H:i')]
    public $start_time = '';

    #[Validate('required|date_format:H:i')]
    public $end_time = '';

    #[Validate('required|integer|min:5|max:240')]
    public $slot_duration = 30;

    #[Validate('required|integer|min:0|max:120')]
    public $break_duration = 15;

    public function mount()
    {
        $this->loadConfigs();
    }

    public function loadConfigs()
    {
        $this->scheduleConfigs = ScheduleConfig::orderBy('name')->get();
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $config = ScheduleConfig::findOrFail($id);

        $this->editingId = $id;
        $this->name = $config->name;
        $this->start_time = \Carbon\Carbon::parse($config->start_time)->format('H:i');
        $this->end_time = \Carbon\Carbon::parse($config->end_time)->format('H:i');
        $this->slot_duration = $config->slot_duration;
        $this->break_duration = $config->break_duration;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        // End time'ın start time'dan büyük olduğunu kontrol et
        if ($this->start_time >= $this->end_time) {
            $this->addError('end_time', 'Bitiş saati başlangıç saatinden büyük olmalıdır.');
            return;
        }

        $data = [
            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'slot_duration' => $this->slot_duration,
            'break_duration' => $this->break_duration,
        ];

        if ($this->editingId) {
            ScheduleConfig::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Zaman ayarları başarıyla güncellendi.');
        } else {
            ScheduleConfig::create($data);
            session()->flash('message', 'Zaman ayarları başarıyla oluşturuldu.');
        }

        $this->resetForm();
        $this->loadConfigs();
    }

    public function delete($id)
    {
        $config = ScheduleConfig::findOrFail($id);
        if ($config->schedules->count() > 0) {
            session()->flash('error', 'Bu zaman ayarı kullanılıyor, silemezsiniz.');
            return;
        }
        $config->delete();
        session()->flash('message', 'Zaman ayarları başarıyla silindi.');
        $this->loadConfigs();
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->name = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->slot_duration = 30;
        $this->break_duration = 15;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.settings.time-settings');
    }
}
