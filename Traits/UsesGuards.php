<?php

namespace Pingu\Permissions\Traits;

use Illuminate\Support\Collection;
use Pingu\Permissions\Exceptions\GuardDoesNotMatch;
use Pingu\Permissions\Guard;

trait UsesGuards
{
    /**
     * @param Role|Permission $roleOrPermission
     *
     * @throws \Pingu\Permissions\Exceptions\GuardDoesNotMatch
     */
    protected function ensureModelSharesGuard($roleOrPermission)
    {
        if (! $this->getGuardNames()->contains($roleOrPermission->guard)) {
            throw GuardDoesNotMatch::create($roleOrPermission->guard, $this->getGuardNames());
        }
    }

    protected function getGuardNames(): Collection
    {
        return Guard::getNames($this);
    }

    protected function getDefaultGuardName(): string
    {
        return Guard::getDefaultName($this);
    }
}
