<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Buffer;
use Exception;

class BoilerReader
{
    const BUFFER_SIZE=600;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * BoilerReader constructor.
     * @param Configuration $configuration
     */
    public function __construct(
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }

    /**
     * @return Buffer
     * @throws Exception
     */
    public function read(): Buffer
    {
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
     * Connect to the boiler
     * @return resource
     * @throws Exception
     */
    private function connect()
    {
        $socket = fsockopen(
            $this->configuration->getBoilerHost(),
            $this->configuration->getBoilerPort(),
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
     * Read the values from the boiler
     *
     * @param resource $socket
     * @return array
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
     * @param resource $socket
     * @return void
     */
    protected function disconnect($socket): void
    {
        if ($socket) {
            fclose($socket);
        }
    }
}
