<?php declare(strict_types=1);

namespace Timothydc\ExactOnline\BaseClient;

class RateLimits
{
    public function __construct(
        protected int $minutelyLimit = 60,
        protected int $minutelyLimitRemaining = 60,
        protected int $minutelyLimitReset = 0, // this is a timestamp of the reset moment
        protected int $dailyLimit = 5000,
        protected int $dailyLimitRemaining = 5000,
        protected int $dailyLimitReset = 0, // this is a timestamp of the reset moment
    ) {
    }

    public function getMinutelyLimit(): int
    {
        return $this->minutelyLimit;
    }

    public function setMinutelyLimit(?int $minutelyLimit): self
    {
        if (null !== $minutelyLimit) {
            $this->minutelyLimit = $minutelyLimit;
        }

        return $this;
    }

    public function getMinutelyLimitRemaining(): int
    {
        return $this->minutelyLimitRemaining;
    }

    public function setMinutelyLimitRemaining(?int $minutelyLimitRemaining): self
    {
        if (null !== $minutelyLimitRemaining) {
            $this->minutelyLimitRemaining = $minutelyLimitRemaining;
        }

        return $this;
    }

    public function getMinutelyLimitReset(): int
    {
        return $this->minutelyLimitReset;
    }

    public function setMinutelyLimitReset(?int $minutelyLimitReset): self
    {
        if (null !== $minutelyLimitReset) {
            $this->minutelyLimitReset = $minutelyLimitReset;
        }

        return $this;
    }

    public function getDailyLimit(): int
    {
        return $this->dailyLimit;
    }

    public function setDailyLimit(?int $dailyLimit): self
    {
        if (null !== $dailyLimit) {
            $this->dailyLimit = $dailyLimit;
        }

        return $this;
    }

    public function getDailyLimitRemaining(): int
    {
        return $this->dailyLimitRemaining;
    }

    public function setDailyLimitRemaining(?int $dailyLimitRemaining): self
    {
        if (null !== $dailyLimitRemaining) {
            $this->dailyLimitRemaining = $dailyLimitRemaining;
        }

        return $this;
    }

    public function getDailyLimitReset(): int
    {
        return $this->dailyLimitReset;
    }

    public function setDailyLimitReset(?int $dailyLimitReset): self
    {
        if (null !== $dailyLimitReset) {
            $this->dailyLimitReset = $dailyLimitReset;
        }

        return $this;
    }
}
