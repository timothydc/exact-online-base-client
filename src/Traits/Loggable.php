<?php

declare(strict_types=1);

namespace TimothyDC\ExactOnline\BaseClient\Traits;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

trait Loggable
{
    protected ?LoggerInterface $logger = null;
    protected string $defaultLogLevel = LogLevel::INFO;

    public function setLogger(?LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    public function setDefaultLogLevel(string $defaultLogLevel): self
    {
        $this->defaultLogLevel = $defaultLogLevel;

        return $this;
    }

    protected function log(string $message, array $context = [], ?string $level = null): void
    {
        if (! $level) {
            $level = $this->defaultLogLevel;
        }

        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }
}
