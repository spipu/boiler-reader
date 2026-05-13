<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\BufferService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:boiler:run', description: 'Read data from Boiler and push to server.')]
class BoilerRunCommand extends Command
{
    private BufferService $bufferService;

    public function __construct(BufferService $bufferService)
    {
        parent::__construct();
        $this->bufferService = $bufferService;
    }

    /**
     * @throws Exception
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bufferService->readDataAndSaveInBuffer($output);
        $this->bufferService->pushDataFromBuffer($output);

        return Command::SUCCESS;
    }
}
