<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
        <h3 class="text-lg font-bold mb-2 text-center">Yeni Derslik Ekle</h3>

        <input type="text" wire:model="form.name" placeholder="Derslik Adı"
               class="p-2 border rounded-md w-full mb-2">

        <select wire:model="form.building_id" class="p-2 border rounded-md w-full mb-2">
            @foreach($this->buildings as $building)
                 <option value="{{$building?->id}}">
                     {{$building?->name}}</option>
            @endforeach
        </select>

        <input type="number" wire:model="form.class_capacity" placeholder="Sınıf Kapasitesi"
               class="p-2 border rounded-md w-full mb-2">

        <input type="number" wire:model="form.exam_capacity" placeholder="Sınav Kapasitesi"
               class="p-2 border rounded-md w-full mb-2">

        <select wire:model="form.type" class="p-2 border rounded-md w-full mb-2">
            <option value="Sınıf">Sınıf</option>
            <option value="Laboratuar">Laboratuvar</option>
            <option value="Atölye">Atölye</option>
        </select>

        <div class="flex justify-end gap-2">
            <button @click="$dispatch('close-create-classroom-form')" class="p-2 bg-gray-500 text-white rounded-md">
                Kapat
            </button>
            <button wire:click="save" class="p-2 bg-green-600 text-white rounded-md">
                Kaydet
            </button>
        </div>
    </div>
</div>
