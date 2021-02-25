<?php

namespace App\Events;

use App\Utils\Wechat\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $tmplMessage;
    public $name;

    /**
     * Create a new event instance.
     * @param Message $message
     * @param $user
     * @param $name
     * @param $tmplMessage
     * @return void
     */
    public function __construct(Message $tmplMessage, $user,$message,$name)
    {
        $this->message = $message;
        $this->tmplMessage = $tmplMessage;
        $this->user = $user;
        $this->name = $name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
