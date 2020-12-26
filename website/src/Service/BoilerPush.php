<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Buffer;
use Exception;

class BoilerPush
{
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
     * @param Buffer $buffer
     * @return void
     * @throws Exception
     */
    public function push(Buffer $buffer): void
    {
        throw new Exception('Not Ready');
    }
}
