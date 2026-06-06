<?php

declare(strict_types=1);

namespace App\Service;

use Spipu\ConfigurationBundle\Service\ConfigurationManager;

class ConfigurationService
{
    private ConfigurationManager $configurationManager;

    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    public function getBoilerHost(): string
    {
        return (string) $this->configurationManager->get('boiler.host');
    }

    public function getBoilerPort(): int
    {
        return (int) $this->configurationManager->get('boiler.port');
    }

    public function getPushApiUrl(): string
    {
        return (string) $this->configurationManager->get('boiler.push.url');
    }

    public function getPushApiName(): string
    {
        return (string) $this->configurationManager->get('boiler.push.api_name');
    }

    public function getPushApiKey(): string
    {
        return (string) $this->configurationManager->getEncrypted('boiler.push.api_key');
    }
}
