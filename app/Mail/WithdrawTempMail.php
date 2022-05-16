<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Core\Repositories\Eloquents\EmailTemplateRepository;

class WithdrawTempMail extends Mailable implements ShouldQueue
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
        $email = $emailRepository->find(config('constants.email.withdraw_temp'));
        $subject = 'Confirm withdraw';
        $content = '<p>Confirm withdraw</p>';
        if ($email) {
            $hashId = md5($data['id'] . env('SECRET_KEY_APP')) . $data['id'];
            $subject = $email->subject;
            $content = $email->content;
            $url = route('wallets.user_confirm_withdraw', ['id' => $hashId]);
            $button = '<a href="' . $url . '" rel="noopener" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#4a6df5;border-top:10px solid #4a6df5;border-right:18px solid #4a6df5;border-bottom:10px solid #4a6df5;border-left:18px solid #4a6df5" target="_blank">Confirm</a>';
            $params = [
                '{FULLNAME}' => $data['fullname'],
                '{AMOUNT}' => number_format($data['amount'] - $data['amount_fee'], 2),
                '{CODE}' => $data['code'],
                '{TIME}' => $data['time'],
                '{URL}' => $url,
                '{BUTTON}' => $button
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
