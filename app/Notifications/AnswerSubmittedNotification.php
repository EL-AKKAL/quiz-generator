<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnswerSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(protected int $quizID, protected int $answerId)
    {
        $this->$quizID = $quizID;
        $this->answerId = $answerId;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(int $quizID, object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("New answer has been submitted for Quiz : {$quizID}");
    }

    public function toArray(int $quizID, int $answerId, object $notifiable): array
    {
        return [
            'quiz_id' => $quizID,
            'answer' => $answerId
        ];
    }
}
