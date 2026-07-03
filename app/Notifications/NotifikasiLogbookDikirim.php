<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/** Notifikasi ke koordinator/admin saat logbook baru dikirim untuk review. */
class NotifikasiLogbookDikirim extends Notification
{
    use Queueable;

    public function __construct(public Logbook $logbook) {}

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
            'anggota' => $this->logbook->anggota?->nama,
            'tipe' => 'logbook_dikirim',
            'pesan' => ($this->logbook->anggota?->nama ?? 'Anggota').' mengirim logbook "'.$this->logbook->judul.'" untuk review.',
            'url' => route('panel.catatan-harian.index'),
        ];
    }
}
