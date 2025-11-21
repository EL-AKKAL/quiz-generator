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
        $this->quizID = $quizID;
        $this->answerId = $answerId;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("New answer has been submitted for Quiz: {$this->quizID}");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'quiz_id' => $this->quizID,
            'answer_id' => $this->answerId,
        ];
    }
}
