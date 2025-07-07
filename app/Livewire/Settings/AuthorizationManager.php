<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Birim; // Kendi Birim modelinizi import edin
use App\Models\Bolum; // Kendi Bolum modelinizi import edin
use App\Models\User;  // Kendi User modelinizi import edin

// Spatie/Laravel-Permission gibi bir paket kullanıyorsanız, bunları da import edin
use Spatie\Permission\Models\Role; // Spatie için
use Spatie\Permission\Models\Permission; // Spatie için

class AuthorizationManager extends Component
{
    public $birims;
    public $bolums;
    public $users; // Tüm kullanıcılar
    public $roles; // Tüm roller
    public $permissions; // Tüm izinler

    public $selectedEntityType = 'birim'; // 'birim' veya 'bolum'
    public $selectedEntityId = null;

    // Seçili birim veya bölümün bilgilerini tutacak değişkenler
    public $currentEntity = null; // Seçili Birim veya Bölüm model nesnesi

    public function mount()
    {
        // Eager loading ile manager ilişkisini yüklüyoruz. 'adi' alanı için User modelinizde "$appends" kullanabilirsiniz
        // veya manager ilişkisini çektikten sonra manager->adi diyerek direkt kullanabilirsiniz.
        $this->birims = Birim::with('manager')->get();
        $this->bolums = Bolum::with('manager')->get();
        $this->users = User::all(); // Tüm kullanıcıları çekiyoruz

        // Spatie/Laravel-Permission gibi bir paket kullanıyorsanız
        $this->roles = Role::all();
        $this->permissions = Permission::all();

        // Varsayılan olarak ilk birimi seç
        $firstBirim = $this->birims->first();
        if ($firstBirim) {
            $this->selectedEntityId = $firstBirim->id;
            $this->currentEntity = $firstBirim; // Seçili varlığı başlangıçta ayarla
        }
    }

    public function selectBirim($birimId)
    {
        $this->selectedEntityType = 'birim';
        $this->selectedEntityId = $birimId;
        $this->currentEntity = $this->birims->firstWhere('id', $birimId);
    }

    public function selectBolum($bolumId)
    {
        $this->selectedEntityType = 'bolum';
        $this->selectedEntityId = $bolumId;
        $this->currentEntity = $this->bolums->firstWhere('id', $bolumId);
    }

    // Seçili birime ait bölümleri döndüren computed property
    public function getBolumsForSelectedBirimProperty()
    {
        if ($this->selectedEntityType === 'birim' && $this->currentEntity) {
            return $this->bolums->where('birim_id', $this->currentEntity->id);
        }
        return collect();
    }

    // Seçili birim veya bölümün yetkililerini döndüren computed property
    // Bu kısım, Spatie veya benzeri bir yetkilendirme paketini gerçek anlamda nasıl entegre edeceğinizi gösteriyor.
    public function getEntityAuthorizationsProperty()
    {
        $authorizations = collect();

        if (!$this->currentEntity) {
            return $authorizations;
        }

        // 1. Sorumlu Kişi (Manager)
        if ($this->currentEntity->manager) {
            $authorizations->push((object)[
                'user_adi' => $this->currentEntity->manager->adi,
                'type' => 'Sorumlu',
                'detail' => $this->selectedEntityType === 'birim' ? 'Birim Sorumlusu (Dekan)' : 'Bölüm Sorumlusu (Bölüm Başkanı)',
                'id' => $this->selectedEntityType . '_manager_' . $this->selectedEntityId,
            ]);
        }

        // 2. Rol Atamaları (Örn: Spatie Laravel-Permission ile)
        // Burada, seçili birim/bölümle ilişkili rolleri çekeceğiz.
        // Eğer rolleriniz birim/bölüm bazlı ise, Spatie'nin yetkilendirme modelini genişletmeniz gerekir.
        // Varsayılan olarak Spatie, global roller atar. Ancak bizim konuştuğumuz "entity_type" ve "entity_id" sütunlarını eklediyseniz,
        // aşağıdaki gibi filtreleyebilirsiniz.
        // Bu kısım, sizin 'model_has_roles' ve 'model_has_permissions' tablolarınıza nasıl veri kaydettiğinize bağlı.

        // Örnek: "Pazarlama Birimi Editörü" gibi bir rolünüz varsa ve kullanıcıya atanmışsa.
        // Bu örnek, "entity_type" ve "entity_id" sütunlarını 'model_has_roles' ve 'model_has_permissions' tablolarınıza eklediğinizi varsayar.

        // Eğer Spatie kullanıyorsanız ve polymorphic ilişkilerle genişlettiyseniz:
        // Spatie'nin Role ve Permission modelleri standart olarak entity_type/id içermez.
        // Bu genellikle custom bir ara tablo sorgusu veya custom Guard/Gate tanımı ile yapılır.
        // Daha basit bir yol olarak:
        // 1. Rollere/İzinlere 'birim_id' veya 'bolum_id' alanı ekleyip ona göre filtrelemek
        // 2. Kullanıcıların "birim/bölüm" bağlamında belirli rollere sahip olup olmadığını kontrol eden bir mekanizma yazmak

        // Şimdilik, Spatie'den rastgele birkaç rol ve izin atayalım.
        // GERÇEK UYGULAMADA: Kullanıcıların, ilgili birim/bölüm bağlamında hangi rollere/izinlere sahip olduğunu sorgulamanız GEREKİR.

        // Örneğin, eğer 'model_has_roles' tablonuzda 'entity_type' ve 'entity_id' sütunlarınız varsa:
        // $usersWithRoles = User::whereHas('roles', function($query) {
        //     $query->where('entity_type', $this->selectedEntityType)
        //           ->where('entity_id', $this->selectedEntityId);
        // })->get();
        // Veya daha iyisi, User modelinizde yetki kontrol metotları yazmak.

        // Simülasyon: Seçili birime/bölüme özel hayali yetkililer ekleyelim
        if ($this->selectedEntityType === 'birim' && $this->selectedEntityId === 1) { // Eğitim Fakültesi (id:1)
            $user = User::find(4); // Zeynep Can (varsayımsal)
            if ($user) {
                $authorizations->push((object)[
                    'user_adi' => $user->adi,
                    'type' => 'Rol',
                    'detail' => 'Akademik Danışman (Eğitim Fakültesi)',
                    'id' => 'akademik_danisman_' . $this->selectedEntityId,
                ]);
            }
        } elseif ($this->selectedEntityType === 'bolum' && $this->selectedEntityId === 101) { // İlköğretim Öğretmenliği (id:101)
            $user = User::find(1); // Prof. Dr. Ahmet Yılmaz (varsayımsal)
            if ($user) {
                $authorizations->push((object)[
                    'user_adi' => $user->adi,
                    'type' => 'İzin',
                    'detail' => 'Sınav Sonuçlarını Görüntüleme',
                    'id' => 'sinav_sonuc_goruntule_' . $this->selectedEntityId,
                ]);
            }
        }

        return $authorizations;
    }

    public function render()
    {
        return view('livewire.settings.authorization-manager');
    }
}
