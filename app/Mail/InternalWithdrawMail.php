<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Core\Repositories\Eloquents\EmailTemplateRepository;

class InternalWithdrawMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;

    /**
     * Create a new message instance.
     *
     * @param $data
     * @return void
     */
    public function __construct($data)
    {
        $emailRepository = new EmailTemplateRepository();
        $email = $emailRepository->find(config('constants.email.internal_withdraw'));
        $subject = 'Internal withdraw';
        $content = '<p>Internal withdraw</p>';
        if ($email) {
            $subject = $email->subject;
            $content = $email->content;
            $params = [
                '{USER_FROM}' => $data['user_from'],
                '{AMOUNT}' => $data['amount'],
                '{TIME}' => $data['time'],
            ];
            $content = strtr($content, $params);
        }
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('emails.send')->with('content', $this->content);
    }
}
