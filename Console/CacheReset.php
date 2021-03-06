<?php

namespace Pingu\Permissions\Console;

use Illuminate\Console\Command;
use Pingu\Permissions\Permissions;

class CacheReset extends Command
{
    protected $signature = 'permission:cache-reset';

    protected $description = 'Reset the permission cache';

    public function handle()
    {
        app(Permissions::class)->flushCache();

        $this->info('Permission cache flushed.');
    }
}
