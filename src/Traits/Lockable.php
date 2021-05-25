<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Traits;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface as Lock;
use Symfony\Component\Lock\Store\SemaphoreStore;

trait Lockable
{
    protected Lock $lock;
    protected string $lockKey = '';
    protected int $lockTimeout = 40;

    /**
     * Opted to use the low level atomic locking (SemaphoreStore variant) of Symfony.
     *
     * @see https://symfony.com/doc/4.4/components/lock.html#usage
     * @return Lock
     */
    protected function getLock(): Lock
    {
        if (! isset($this->lock)) {
            $this->lock = (new LockFactory(new SemaphoreStore))
                ->createLock($this->lockKey, $this->lockTimeout);
        }

        return $this->lock;
    }

    public function setLockKey(string $lockKey): void
    {
        $this->lockKey = $lockKey;
    }

    public function setLockTimeout(int $lockTimeout): void
    {
        $this->lockTimeout = $lockTimeout;
    }
}
