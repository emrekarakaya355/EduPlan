<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manageRoles = Permission::firstOrCreate(['name' => 'rol_yonet']);
        $managePermissions = Permission::firstOrCreate(['name' => 'izin_yonet']);
        $viewSystemLogs = Permission::firstOrCreate(['name' => 'sistem_loglari_goruntule']);


        Permission::firstOrCreate(['name' => 'kısıtlama_gir']);            // Kendi kısıtlamalarını veya yetkili olduğu kapsamdaki kısıtlamaları girme
        Permission::firstOrCreate(['name' => 'kısıtlama_goruntule']);     // Kısıtlamaları görüntüleme
        Permission::firstOrCreate(['name' => 'kısıtlama_onayla']);        // Kısıtlamaları onaylama

        Permission::firstOrCreate(['name' => 'ders_tanimla']);            // Yeni ders tanımlama, mevcut ders bilgilerini düzenleme
        Permission::firstOrCreate(['name' => 'ders_goruntule']);          // Ders listelerini görüntüleme

        Permission::firstOrCreate(['name' => 'derslik_tanimla']);         // Yeni derslik tanımlama, düzenleme
        Permission::firstOrCreate(['name' => 'derslik_goruntule']);       // Derslik listelerini görüntüleme

        Permission::firstOrCreate(['name' => 'ders_programi_planla']);             // Ders programı taslağı oluşturma/düzenleme
        Permission::firstOrCreate(['name' => 'ders_programi_goruntule_taslak']);   // Taslak ders programını görüntüleme
        Permission::firstOrCreate(['name' => 'ders_programi_onayla']);             // Oluşturulmuş ders programını onaylama
        Permission::firstOrCreate(['name' => 'ders_programi_onay_durumu_goruntule']);// Programın onay durumunu görme
        Permission::firstOrCreate(['name' => 'ders_programi_yayinla']);            // Onaylanmış ders programını genel kullanıma açma
        Permission::firstOrCreate(['name' => 'ders_programi_goruntule_yayinlanmis']);// Yayınlanmış programı görüntüleme

        Permission::firstOrCreate(['name' => 'rapor_al']);               // Çeşitli ders programı raporları oluşturma/indirme
        Permission::firstOrCreate(['name' => 'rapor_goruntule']);         // Raporları görüntüleme

        Permission::firstOrCreate(['name' => 'kullanici_yonetimi']);      // Kullanıcıları ekleme, düzenleme, silme
        Permission::firstOrCreate(['name' => 'rol_yonetimi']);            // Rollere izin atama, rol oluşturma/düzenleme (sadece Süper Yönetici)
        Permission::firstOrCreate(['name' => 'kurumsal_birim_yonetimi']); // Birim/Bölüm gibi kurumsal yapıları ekleme/düzenleme
        Permission::firstOrCreate(['name' => 'rol_ata_kapsamli']);

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']); // Süper Yönetici: Her şeye tam erişim
        $adminRole = Role::firstOrCreate(['name' => 'admin']);                     // Süper Yönetici: Tüm sistem yetkilerine sahip
        $birimYoneticisiRole = Role::firstOrCreate(['name' => 'birim_yoneticisi']);                     // Dekan: Fakülte/Yüksekokul düzeyinde yetkili
        $birimSorumlusuRole = Role::firstOrCreate(['name' => 'birim_sorumlusu']);// Fakülte Sekreteri: İdari görevler
        $bolumSorumlusuRole = Role::firstOrCreate(['name' => 'bolum_sorumlusu']);     // Bölüm Başkanı: Bölüm düzeyinde yetkili
        $dersKoordinatoruRole = Role::firstOrCreate(['name' => 'ders_koordinatoru']);// Ders Programı Koordinatörü: Program hazırlama odaklı
        $ogretimElemaniRole = Role::firstOrCreate(['name' => 'ogretim_elemani']); // Öğretim Elemanı: En temel kullanıcı
        $ogrenciRole = Role::firstOrCreate(['name' => 'ogrenci']);                 // Öğrenci: Ders programını görüntüleme vb.

        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole->givePermissionTo([
            'kullanici_yonetimi',
            'kurumsal_birim_yonetimi',
            'derslik_tanimla',         // Derslikleri genel olarak tanımlayabilir
            'derslik_goruntule',
            'ders_tanimla',            // Dersleri genel olarak tanımlayabilir
            'ders_goruntule',
            'kısıtlama_goruntule',
            'ders_programi_goruntule_taslak',
            'ders_programi_goruntule_yayinlanmis',
            'rapor_goruntule',
            'rapor_al',
        ]);
        $birimYoneticisiRole->givePermissionTo([
            'kısıtlama_goruntule',
            'kısıtlama_onayla',
            'ders_goruntule',
            'derslik_goruntule',
            'ders_programi_goruntule_taslak',
            'ders_programi_goruntule_yayinlanmis',
            'ders_programi_onayla',
            'ders_programi_yayinla',
            'rapor_goruntule',
            'rapor_al',
            'kurumsal_birim_yonetimi',
            'rol_ata_kapsamli',
        ]);

        $birimSorumlusuRole->givePermissionTo([
            'kısıtlama_goruntule',
            'ders_goruntule',
            'derslik_goruntule',
            'ders_programi_goruntule_taslak',
            'ders_programi_goruntule_yayinlanmis',
            'rapor_goruntule',
        ]);

        $bolumSorumlusuRole->givePermissionTo([
            'kısıtlama_gir',
            'kısıtlama_goruntule',
            'ders_tanimla',
            'ders_goruntule',
            'derslik_goruntule',
            'ders_programi_planla',
            'ders_programi_goruntule_taslak',
            'ders_programi_goruntule_yayinlanmis',
            'rapor_goruntule',
            'rapor_al',
            'kurumsal_birim_yonetimi', // Kendi bölümünü düzenleme, derslik ekleme (kendi kapsamı için)
            'rol_ata_kapsamli',        // Kendi bölümü içinde rol atama yetkisi
        ]);

        $dersKoordinatoruRole->givePermissionTo([
            'kısıtlama_gir',
            'kısıtlama_goruntule',
            'ders_tanimla',
            'ders_goruntule',
            'derslik_goruntule',
            'ders_programi_planla',
            'ders_programi_goruntule_taslak',
            'ders_programi_goruntule_yayinlanmis',
        ]);

        $ogretimElemaniRole->givePermissionTo([
            'kısıtlama_gir',                   // Sadece kendi kısıtlamaları için yetkili olacak (Policy veya Gate ile kontrol edilecek)
            'ders_programi_goruntule_yayinlanmis',
            'ders_goruntule',                 // Kendi verdiği dersleri veya genel ders listesini
            'derslik_goruntule',              // Derslik bilgilerini
        ]);

        $ogrenciRole->givePermissionTo([
            'ders_programi_goruntule_yayinlanmis',
        ]);
    }
}
