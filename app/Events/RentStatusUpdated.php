<?php
namespace App\Events;

use App\Models\Rent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class RentStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rent;

    public function __construct(Rent $rent)
    {
        $this->rent = $rent;
    }

    public function broadcastOn()
    {
        return new Channel('rent-status.' . $this->rent->customer_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->rent->id,
            'item_id' => $this->rent->item_id,
            'status' => $this->rent->rental_status,
            'item_status' => $this->rent->item->item_status,
        ];
    }

    public function broadcastAs()
    {
        return 'RentStatusUpdated';
    }
}
