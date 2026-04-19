<?php

namespace App\Notifications;

use App\Models\Station;
use Illuminate\Notifications\Notification;

class StationStatusChanged extends Notification
{
    public function __construct(public Station $station) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'station_id'   => $this->station->id,
            'station_name' => $this->station->name,
            'message'      => "La station « {$this->station->name} » est maintenant disponible.",
        ];
    }
}
