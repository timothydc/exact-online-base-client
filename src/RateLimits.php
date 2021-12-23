<?php

namespace PolarisDC\ExactOnline\BaseClient;

class RateLimits
{
    protected int $minutelyLimit = 60;
    protected int $minutelyLimitRemaining = 60;
    protected int $minutelyLimitReset = 0; // this is a timestamp of the reset moment
    protected int $dailyLimit = 5000;
    protected int $dailyLimitRemaining = 5000;
    protected int $dailyLimitReset = 0; // this is a timestamp of the reset moment

    public function __construct(
        ?int $minutelyLimit,
        ?int $minutelyLimitRemaining,
        ?int $minutelyLimitReset,
        ?int $dailyLimit,
        ?int $dailyLimitRemaining,
        ?int $dailyLimitReset
    )
    {
        $this->minutelyLimit = $minutelyLimit;
        $this->minutelyLimitRemaining = $minutelyLimitRemaining;
        $this->minutelyLimitReset = $minutelyLimitReset;
        $this->dailyLimit = $dailyLimit;
        $this->dailyLimitRemaining = $dailyLimitRemaining;
        $this->dailyLimitReset = $dailyLimitReset;
    }

    public function getMinutelyLimit(): int
    {
        return $this->minutelyLimit;
    }

    public function setMinutelyLimit(?int $minutelyLimit): RateLimits
    {
        if (! \is_null($minutelyLimit)) {
            $this->minutelyLimit = $minutelyLimit;
        }

        return $this;
    }

    public function getMinutelyLimitRemaining(): int
    {
        return $this->minutelyLimitRemaining;
    }

    public function setMinutelyLimitRemaining(?int $minutelyLimitRemaining): RateLimits
    {
        if (! \is_null($minutelyLimitRemaining)) {
            $this->minutelyLimitRemaining = $minutelyLimitRemaining;
        }

        return $this;
    }

    public function getMinutelyLimitReset(): int
    {
        return $this->minutelyLimitReset;
    }

    public function setMinutelyLimitReset(?int $minutelyLimitReset): RateLimits
    {
        if (! \is_null($minutelyLimitReset)) {
            $this->minutelyLimitReset = $minutelyLimitReset;
        }

        return $this;
    }

    public function getDailyLimit(): int
    {
        return $this->dailyLimit;
    }

    public function setDailyLimit(?int $dailyLimit): RateLimits
    {
        if (! \is_null($dailyLimit)) {
            $this->dailyLimit = $dailyLimit;
        }

        return $this;
    }

    public function getDailyLimitRemaining(): int
    {
        return $this->dailyLimitRemaining;
    }

    public function setDailyLimitRemaining(?int $dailyLimitRemaining): RateLimits
    {
        if (! \is_null($dailyLimitRemaining)) {
            $this->dailyLimitRemaining = $dailyLimitRemaining;
        }

        return $this;
    }

    public function getDailyLimitReset(): int
    {
        return $this->dailyLimitReset;
    }

    public function setDailyLimitReset(?int $dailyLimitReset): RateLimits
    {
        if (! \is_null($dailyLimitReset)) {
            $this->dailyLimitReset = $dailyLimitReset;
        }

        return $this;
    }
}