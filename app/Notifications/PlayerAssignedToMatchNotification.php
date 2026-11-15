<?php

namespace App\Notifications;

use App\Models\GameMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PlayerAssignedToMatchNotification extends Notification
{
    use Queueable;

    public function __construct(
        public GameMatch $match
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'match_name' => $this->match->match_name,
            'match_date' => $this->match->match_date,
            'location' => $this->match->location,
        ];
    }
}