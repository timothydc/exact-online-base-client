<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Support;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

trait Loggable
{
    protected ?LoggerInterface $logger = null;

    protected function log(string $message, array $context = [], string $level = LogLevel::DEBUG): void
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }
}
