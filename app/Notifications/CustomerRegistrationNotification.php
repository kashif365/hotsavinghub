<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Customer;

class CustomerRegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $customer;

    /**
     * Create a new notification instance.
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
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
            ->subject('🎉 Welcome to Social Offerz!')
            ->greeting('Hello ' . $this->customer->name . '!')
            ->line('Welcome to Social Offerz! We\'re excited to have you join our community of smart shoppers.')
            ->line('**Your Account Details:**')
            ->line('👤 **Name:** ' . $this->customer->name)
            ->line('📧 **Email:** ' . $this->customer->email)
            ->line('📅 **Registration Date:** ' . $this->customer->created_at->format('F d, Y'))
            ->line('**What\'s Next?**')
            ->line('✅ Start browsing our exclusive deals and coupons')
            ->line('✅ Subscribe to our newsletter for the latest offers')
            ->line('✅ Follow us on social media for instant updates')
            ->action('Start Shopping & Save Money', route('home'))
            ->line('Thank you for choosing Social Offerz!')
            ->line('Happy Shopping!')
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
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'registration_date' => $this->customer->created_at,
        ];
    }
}

