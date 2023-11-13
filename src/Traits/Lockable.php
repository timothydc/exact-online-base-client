<?php

declare(strict_types=1);

namespace TimothyDC\ExactOnline\BaseClient\Traits;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface as Lock;
use Symfony\Component\Lock\Store\SemaphoreStore;

trait Lockable
{
    protected Lock $lock;
    protected string $lockKey = '';
    protected int $lockTimeout = 40;

    public function setLockKey(string $lockKey): void
    {
        $this->lockKey = $lockKey;
    }

    public function setLockTimeout(int $lockTimeout): void
    {
        $this->lockTimeout = $lockTimeout;
    }

    /**
     * Opted to use the low level atomic locking (SemaphoreStore variant) of Symfony.
     *
     * @see https://symfony.com/doc/4.4/components/lock.html#usage
     */
    protected function getLock(string $lockKey = '', int $lockTimeout = 0): Lock
    {
        if (! isset($this->lock)) {
            $this->lock = (new LockFactory(new SemaphoreStore()))
                ->createLock($lockKey ?: $this->lockKey, $lockTimeout ?: $this->lockTimeout);
        }

        return $this->lock;
    }
}
