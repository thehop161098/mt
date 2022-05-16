<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Core\Repositories\Eloquents\EmailTemplateRepository;

class VerifyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $subject;
    public $content;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct($user)
    {
        $emailRepository = new EmailTemplateRepository();
        $email = $emailRepository->find(config('constants.email.verify_account'));
        $subject = 'Email verify Account';
        $content = '<p>Email Verify Account</p>';
        if ($email) {
            $subject = $email->subject;
            $content = $email->content;
            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(60),
                [
                    'id' => $user->id,
                    'hash' => sha1($user->email),
                ]
            );
            $button = '<a href="' . $verifyUrl . '" rel="noopener" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#4a6df5;border-top:10px solid #4a6df5;border-right:18px solid #4a6df5;border-bottom:10px solid #4a6df5;border-left:18px solid #4a6df5" target="_blank">Verify Account</a>';
            $params = [
                '{USER}' => $user->full_name,
                '{URL}' => $verifyUrl,
                '{BUTTON}' => $button
            ];
            $content = strtr($content, $params);
        }
        $this->user = $user;
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
