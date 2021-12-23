<?php

namespace PolarisDC\ExactOnline\BaseClient;

class RateLimits
{
    protected ?int $minutelyLimit;
    protected ?int $minutelyLimitRemaining;
    protected ?int $minutelyLimitReset;
    protected ?int $dailyLimit;
    protected ?int $dailyLimitRemaining;
    protected ?int $dailyLimitReset;

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

    public function getMinutelyLimit(): ?int
    {
        return $this->minutelyLimit;
    }

    public function setMinutelyLimit(?int $minutelyLimit): RateLimits
    {
        $this->minutelyLimit = $minutelyLimit;
        return $this;
    }

    public function getMinutelyLimitRemaining(): ?int
    {
        return $this->minutelyLimitRemaining;
    }

    public function setMinutelyLimitRemaining(?int $minutelyLimitRemaining): RateLimits
    {
        $this->minutelyLimitRemaining = $minutelyLimitRemaining;
        return $this;
    }

    public function getMinutelyLimitReset(): ?int
    {
        return $this->minutelyLimitReset;
    }

    public function setMinutelyLimitReset(?int $minutelyLimitReset): RateLimits
    {
        $this->minutelyLimitReset = $minutelyLimitReset;
        return $this;
    }

    public function getDailyLimit(): ?int
    {
        return $this->dailyLimit;
    }

    public function setDailyLimit(?int $dailyLimit): RateLimits
    {
        $this->dailyLimit = $dailyLimit;
        return $this;
    }

    public function getDailyLimitRemaining(): ?int
    {
        return $this->dailyLimitRemaining;
    }

    public function setDailyLimitRemaining(?int $dailyLimitRemaining): RateLimits
    {
        $this->dailyLimitRemaining = $dailyLimitRemaining;
        return $this;
    }

    public function getDailyLimitReset(): ?int
    {
        return $this->dailyLimitReset;
    }

    public function setDailyLimitReset(?int $dailyLimitReset): RateLimits
    {
        $this->dailyLimitReset = $dailyLimitReset;
        return $this;
    }
}