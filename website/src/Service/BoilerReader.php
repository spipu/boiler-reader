<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Buffer;
use Exception;

class BoilerReader
{
    private const BUFFER_SIZE = 600;

    private ConfigurationService $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * @throws Exception
     */
    public function read(): Buffer
    {
        $socket = null;
        try {
            $socket = $this->connect();
            $values = $this->readValues($socket);
        } finally {
            $this->disconnect($socket);
        }

        $buffer = new Buffer();
        $buffer
            ->setNbTry(0)
            ->setTime(time())
            ->setData(json_encode($values));

        return $buffer;
    }

    /**
     * @return resource
     * @throws Exception
     */
    private function connect()
    {
        $socket = fsockopen(
            $this->configurationService->getBoilerHost(),
            $this->configurationService->getBoilerPort(),
            $errorNumber,
            $errorString,
            10
        );

        if (!$socket) {
            throw new Exception("$errorString ($errorNumber)");
        }

        return $socket;
    }

    /**
     * @param resource $socket
     * @throws Exception
     */
    protected function readValues($socket): array
    {
        $buffer = '';
        $bufferIsOk = false;

        $nbIteration = 0;
        while (!$bufferIsOk) {
            $buffer = fgets($socket, self::BUFFER_SIZE);
            if (substr($buffer, 0, 2) === 'pm') {
                $bufferIsOk = true;
            }
            $nbIteration++;
            if ($nbIteration > 1000) {
                throw new Exception('Impossible to read valid values');
            }
        }

        $values = explode(' ', trim($buffer));
        array_shift($values);

        return $values;
    }

    /**
     * @param resource|null $socket
     */
    protected function disconnect($socket): void
    {
        if ($socket) {
            fclose($socket);
        }
    }
}
