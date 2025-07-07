<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Bolum;
use App\Models\Birim;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
     public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): Response
    {
        // Kullanıcının yetkili olduğu herhangi bir bölüm veya birim varsa ve 'view schedules' iznine sahipse
        if ($user->hasPermissionTo('view schedules')) { // 'view schedules' izni herhangi bir kapsamda var mı?
            // Ayrıca getAuthorizedDepartments/Units kontrolü de yapılabilir:
            if ($user->getAuthorizedBolums()->isNotEmpty() || $user->getAuthorizedBirims()->isNotEmpty()) {
                return Response::allow();
            }
        }
        return Response::deny('Ders programlarını listeleme yetkiniz yok.');
    }

    // Belirli Bir Ders Programını Görüntüleme
    public function view(User $user, Schedule $schedule): Response
    {
        $bolum = $schedule->bolum;
        $birim = $bolum->birim;

        // 'view schedules' iznine, ilgili bölüm veya birim kapsamında sahip mi?
        if ($user->hasPermissionTo('view schedules', $bolum) || $user->hasPermissionTo('view schedules', $birim)) {
            return Response::allow();
        }
        return Response::deny('Bu ders programını görüntüleme yetkiniz yok.');
    }

    // Yeni Ders Programı Oluşturma
    public function create(User $user): Response
    {
        // Bu metodda doğrudan bir Schedule nesnesi yoktur.
        // Kullanıcının genel olarak 'create schedules' iznine sahip olup olmadığını kontrol ederiz.
        // Hangi bölümde oluşturulacağı kontrolü, Controller veya FormRequest'te yapılmalıdır
        // (`Auth()->user()->getAuthorizedBolums()->contains($requested_bolum_id)` gibi).
        if ($user->hasPermissionTo('create schedules')) {
            return Response::allow();
        }
        return Response::deny('Yeni ders programı oluşturma yetkiniz yok.');
    }

    // Mevcut Bir Ders Programını Güncelleme
    public function update(User $user, Schedule $schedule): Response
    {
        $bolum = $schedule->bolum;
        $birim = $bolum->birim;

        if ($user->hasPermissionTo('edit schedules', $bolum) || $user->hasPermissionTo('edit schedules', $birim)) {
            return Response::allow();
        }
        return Response::deny('Bu ders programını düzenleme yetkiniz yok.');
    }

    // Ders Programını Silme
    public function delete(User $user, Schedule $schedule): Response
    {
        $bolum = $schedule->bolum;
        $birim = $bolum->birim;

        if ($user->hasPermissionTo('delete schedules', $bolum) || $user->hasPermissionTo('delete schedules', $birim)) {
            return Response::allow();
        }
        return Response::deny('Bu ders programını silme yetkiniz yok.');
    }

    // Özel İşlem: Ders Programını Onaylama
    public function approve(User $user, Schedule $schedule): Response
    {
        $bolum = $schedule->bolum;
        $birim = $bolum->birim;

        if ($user->hasPermissionTo('approve schedules', $bolum) || $user->hasPermissionTo('approve schedules', $birim)) {
            return Response::allow();
        }
        return Response::deny('Bu ders programını onaylama yetkiniz yok.');
    }

    public function publish(User $user, Schedule $schedule): Response
    {
        $bolum = $schedule->bolum;
        $birim = $bolum->birim;

        if ($user->hasPermissionTo('publish schedules', $bolum) || $user->hasPermissionTo('publish schedules', $birim)) {
            return Response::allow();
        }
        return Response::deny('Bu ders programını yayımlama yetkiniz yok.');
    }
}
