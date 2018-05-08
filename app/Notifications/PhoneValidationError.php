<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PhoneValidationError extends Notification
{
    use Queueable;

    protected $msg;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($msg)
    {
        $this->msg = $msg;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack', 'database'];
    }

    public function toDatabase($notifiable){
		return ['title' => 'Phone Validation Error', 'content' => $this->msg];
    }


	public function toSlack(){
		return (new SlackMessage)
			->from('Tikis')
			->to('#web')
			->image('https://ljconciertos.com/assets/logos/logo.png')
			->content('Phone Validation Error: ' . $this->msg);
	}

}
