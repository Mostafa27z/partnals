<?php

namespace App\Notifications;

use App\Models\Target;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TargetAssignedNotification extends Notification
{
    use Queueable;

    protected $target;

    /**
     * Create a new notification instance.
     */
    public function __construct(Target $target)
    {
        $this->target = $target;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'target_id' => $this->target->id,
            'target_name' => $this->target->name,
            'message' => 'تم إسناد مستهدف جديد لك: ' . $this->target->name,
            'type' => 'assigned'
        ];
    }
}
