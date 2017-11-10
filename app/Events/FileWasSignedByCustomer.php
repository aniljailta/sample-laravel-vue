<?php

namespace App\Events;

use App\Models\FileSign;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FileWasSignedByCustomer
{
    use SerializesModels;

    public $order;
    public $fileSign;

    /**
     * Create a new event instance.
     *
     * @param Order    $order
     * @param FileSign $fileSign
     */
    public function __construct(Order $order, FileSign $fileSign)
    {
        $this->order = $order;
        $this->fileSign = $fileSign;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['order.' . $this->order->id];
    }
}
