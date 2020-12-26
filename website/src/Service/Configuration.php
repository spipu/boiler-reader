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
     * @var int
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
     * @var bool
     */
    private $disableHttpsVerify;

    /**
     * Configuration constructor.
     * @param string $boilerHost
     * @param int $boilerPort
     * @param string $pushApiUrl
     * @param string $pushApiName
     * @param string $pushApiKey
     * @param bool $disableHttpsVerify
     */
    public function __construct(
        string $boilerHost,
        int $boilerPort,
        string $pushApiUrl,
        string $pushApiName,
        string $pushApiKey,
        bool $disableHttpsVerify = false
    ) {
        $this->boilerHost = $boilerHost;
        $this->boilerPort = $boilerPort;
        $this->pushApiUrl = $pushApiUrl;
        $this->pushApiName = $pushApiName;
        $this->pushApiKey = $pushApiKey;
        $this->disableHttpsVerify = $disableHttpsVerify;
    }

    /**
     * @return string
     */
    public function getBoilerHost(): string
    {
        return $this->boilerHost;
    }

    /**
     * @return int
     */
    public function getBoilerPort(): int
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

    /**
     * @return bool
     */
    public function isDisableHttpsVerify(): bool
    {
        return $this->disableHttpsVerify;
    }
}
