<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi in-app ke pemilik logbook saat catatan harian disetujui/ditolak.
 */
class NotifikasiLogbookDireview extends Notification
{
    use Queueable;

    public function __construct(
        public Logbook $logbook,
        public string $statusLabel,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'logbook_id' => $this->logbook->id,
            'judul' => $this->logbook->judul,
            'status' => $this->logbook->status,
            'status_label' => $this->statusLabel,
            'catatan_reviewer' => $this->logbook->catatan_reviewer,
            'pesan' => 'Catatan "'.$this->logbook->judul.'" '.$this->statusLabel.'.',
        ];
    }
}
