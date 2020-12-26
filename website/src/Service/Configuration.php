<?php
declare(strict_types = 1);

namespace App\Service;

class Configuration
{
    /**
     * @var string
     */
    private $boilerHost;

    /**
     * @var string
     */
    private $boilerPort;

    /**
     * @var string
     */
    private $pushApiUrl;

    /**
     * @var string
     */
    private $pushApiName;

    /**
     * @var string
     */
    private $pushApiKey;

    /**
     * Configuration constructor.
     * @param string $boilerHost
     * @param string $boilerPort
     * @param string $pushApiUrl
     * @param string $pushApiName
     * @param string $pushApiKey
     */
    public function __construct(
        string $boilerHost,
        string $boilerPort,
        string $pushApiUrl,
        string $pushApiName,
        string $pushApiKey
    ) {
        $this->boilerHost = $boilerHost;
        $this->boilerPort = $boilerPort;
        $this->pushApiUrl = $pushApiUrl;
        $this->pushApiName = $pushApiName;
        $this->pushApiKey = $pushApiKey;
    }

    /**
     * @return string
     */
    public function getBoilerHost(): string
    {
        return $this->boilerHost;
    }

    /**
     * @return string
     */
    public function getBoilerPort(): string
    {
        return $this->boilerPort;
    }

    /**
     * @return string
     */
    public function getPushApiUrl(): string
    {
        return $this->pushApiUrl;
    }

    /**
     * @return string
     */
    public function getPushApiName(): string
    {
        return $this->pushApiName;
    }

    /**
     * @return string
     */
    public function getPushApiKey(): string
    {
        return $this->pushApiKey;
    }
}
