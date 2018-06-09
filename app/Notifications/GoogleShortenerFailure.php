<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GoogleShortenerFailure extends Notification
{
    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
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
    	return ['title' => 'Google Shortener Exception', 'content' => $this->message];
    }

	public function toSlack($notifiable){
		return (new SlackMessage)
			->from('Tikis')
			->to('#web')
			->image('https://illusiontouringentertainment.com/assets/logos/logo.png')
			->content('Google Shortener Exception: ' . $this->message);
	}

}
