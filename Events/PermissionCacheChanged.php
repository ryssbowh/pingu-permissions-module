<?php

namespace Pingu\Permissions\Events;

use Illuminate\Queue\SerializesModels;

class PermissionCacheChanged
{
    use SerializesModels;

    public $permission;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($permission = null)
    {
        $this->permission = $permission;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
