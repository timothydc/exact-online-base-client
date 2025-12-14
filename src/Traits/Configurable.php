<?php

declare(strict_types=1);

namespace Timothydc\ExactOnline\BaseClient\Traits;

use Timothydc\ExactOnline\BaseClient\Interfaces\ConfigurationInterface;

trait Configurable
{
    protected ConfigurationInterface $configuration;

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    public function setConfiguration(ConfigurationInterface $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }
}
