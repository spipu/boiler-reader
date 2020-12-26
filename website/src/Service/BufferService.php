<?php
declare(strict_types = 1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Output\OutputInterface;

class BufferService
{
    /**
     * @var BoilerReader
     */
    private $boilerReader;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * BufferService constructor.
     * @param BoilerReader $boilerReader
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        BoilerReader $boilerReader,
        EntityManagerInterface $entityManager
    ) {
        $this->boilerReader = $boilerReader;
        $this->entityManager = $entityManager;
    }

    /**
     * @param OutputInterface|null $output
     * @throws Exception
     */
    public function readDataAndSaveInBuffer(?OutputInterface $output): void
    {
        if ($output) {
            $output->writeln('Read from boiler');
        }

        $buffer = $this->boilerReader->read();

        if ($output) {
            $output->writeln('Save in buffer');
        }

        $this->entityManager->persist($buffer);
        $this->entityManager->flush();

        if ($output) {
            $output->writeln(sprintf(' => buffer id: %d', $buffer->getId()));
        }
    }
}
