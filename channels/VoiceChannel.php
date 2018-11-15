<?php
namespace app\channels;

use webzop\notifications\Channel;
use webzop\notifications\Notification;

class VoiceChannel extends Channel
{
    /**
     * Send the given notification.
     *
     * @param Notification $notification
     * @return void
     */
    public function send(Notification $notification)
    {
        // die('VoiceChannel send okkk!!');
        // use $notification->getTitle() ou $notification->getDescription();
        // Send your notification in this channel...
    }

}
?>