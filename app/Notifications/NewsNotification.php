<?php

namespace App\Notifications;

use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $news;
    public function __construct(News $news)
    {
        //
        $this->news = $news;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
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
        $isCreator = $notifiable->id === $this->news->user_id;
        $message = $isCreator
            ? 'تم إنشاء الخبر بنجاح: ' . $this->news->name
            : 'تم إنشاء خبر جديد: ' . $this->news->name . ' بواسطة ' . $this->news->user->name;
        return [

            'news_id' => $this->news->id,
            'title' => $this->news->title,
            'content' => $this->news->name,
            'message' => $message,

        ];
    }
}
