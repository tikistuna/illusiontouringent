<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LowBalanceNexmo extends Notification
{

    private $balance;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($balance)
    {
        $this->balance = $balance;
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

	/**
	 * @param $notifiable
	 * @return array
	 */
	public function toDatabase($notifiable){
    	return ['title' => 'Low Balance Warning', 'content' => $this->balance];
    }

    public function toSlack($notifiable){
		return (new SlackMessage)
			->from('Tikis')
			->to('#web')
			->image('https://ljconciertos.com/assets/logos/logo.png')
			->content('Low Balance Warning: ' . $this->balance);
    }
}
