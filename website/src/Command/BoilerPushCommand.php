<?php
declare(strict_types = 1);

namespace App\Command;

use App\Service\BufferService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BoilerPushCommand extends Command
{
    /**
     * @var BufferService
     */
    private $bufferService;

    /**
     * ConfigurationCommand constructor.
     * @param BufferService $bufferService
     * @param null|string $name
     */
    public function __construct(
        BufferService $bufferService,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->bufferService = $bufferService;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('app:boiler:push')
            ->setDescription('Push data to Boiler.')
            ->setHelp('This command will push data to server')
        ;
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bufferService->pushDataFromBuffer($output);

        return 0;
    }
}
