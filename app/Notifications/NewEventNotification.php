<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Events;

class NewEventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Events $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 New Event: ' . $this->event->event_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We have exciting news! A new event has been added to our platform.')
            ->line('**Event Details:**')
            ->line('📅 **Event:** ' . $this->event->event_name)
            ->line('📝 **Description:** ' . $this->event->event_description)
            ->line('🏷️ **Type:** ' . $this->event->event_type)
            ->line('📅 **Start Date:** ' . $this->event->start_date)
            ->line('📅 **End Date:** ' . $this->event->end_date)
            ->action('View Event & Get Coupons', route('event.detail', $this->event->seo_url))
            ->line('Don\'t miss out on exclusive deals and discounts!')
            ->line('Thank you for being a valued subscriber!')
            ->salutation('Best regards,<br>Social Offerz Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->event_name,
            'event_type' => $this->event->event_type,
        ];
    }
}
