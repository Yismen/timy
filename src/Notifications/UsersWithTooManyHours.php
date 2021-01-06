<?php

namespace Dainsys\Timy\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsersWithTooManyHours extends Notification
{
    use Queueable;

    public float $threshold;

    public Collection $timy_users;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Collection $timy_users)
    {
        $this->threshold = config('timy.daily_hours_threshold', $hours = 8.5);
        $this->timy_users = $timy_users;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('timy::titles.users_with_too_many_hours.subject'))
            ->markdown('timy::mails.users_with_too_many_hours', [
                'notifiable' => $notifiable,
                'threshold' => $this->threshold,
                'timy_users' => $this->timy_users,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
