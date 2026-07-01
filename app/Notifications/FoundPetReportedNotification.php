<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\FoundPetReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class FoundPetReportedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public FoundPetReport $report,
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $pet = $this->report->pet;

        return (new MailMessage)
            ->subject(__('Reporte: encontraron a :name', ['name' => $pet->name]))
            ->greeting(__('¡Buenas noticias!'))
            ->line(__('Alguien reportó que encontró a :name.', ['name' => $pet->name]))
            ->when($this->report->finder_name, fn ($m) => $m->line(__('Nombre: :name', ['name' => $this->report->finder_name])))
            ->when($this->report->finder_phone, fn ($m) => $m->line(__('Teléfono: :phone', ['phone' => $this->report->finder_phone])))
            ->when($this->report->message, fn ($m) => $m->line($this->report->message))
            ->action(__('Ver mis mascotas'), route('pets.index'));
    }
}
