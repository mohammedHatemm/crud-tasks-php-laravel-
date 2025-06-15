<?php

namespace App\Notifications;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewCategoryNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        $isCreator = $notifiable->id === auth()->id();
        $message = $isCreator
            ? 'تم إنشاء التصنيف بنجاح: ' . $this->category->name
            : 'تم إنشاء تصنيف جديد: ' . $this->category->name . ' بواسطة ' . auth()->user()->name;

        return [
            'category_id' => $this->category->id,
            'name' => $this->category->name,
            'description' => $this->category->description,
            'message' => $message,
            'created_by' => auth()->user()->name,
            'is_creator' => $isCreator,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
