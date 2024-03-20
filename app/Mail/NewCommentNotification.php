<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $commenterName;
    public $commentDetail;

    public function __construct($commenterName, $commentDetail)
    {
        $this->commenterName = $commenterName;
        $this->commentDetail = $commentDetail;
    }

    public function build()
    {
        return $this->view('mail.new_comment_notification');
    }
}
