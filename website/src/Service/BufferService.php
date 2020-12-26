<?php
declare(strict_types = 1);

namespace App\Service;

use App\Repository\BufferRepository;
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
     * @var BufferRepository
     */
    private $bufferRepository;

    /**
     * @var BoilerPush
     */
    private $boilerPush;

    /**
     * BufferService constructor.
     * @param BoilerReader $boilerReader
     * @param EntityManagerInterface $entityManager
     * @param BufferRepository $bufferRepository
     * @param BoilerPush $boilerPush
     */
    public function __construct(
        BoilerReader $boilerReader,
        EntityManagerInterface $entityManager,
        BufferRepository $bufferRepository,
        BoilerPush $boilerPush
    ) {
        $this->boilerReader = $boilerReader;
        $this->entityManager = $entityManager;
        $this->bufferRepository = $bufferRepository;
        $this->boilerPush = $boilerPush;
    }

    /**
     * @param OutputInterface $output
     * @throws Exception
     */
    public function readDataAndSaveInBuffer(OutputInterface $output): void
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

    /**
     * @param OutputInterface $output
     * @return void
     * @throws Exception
     */
    public function pushDataFromBuffer(OutputInterface $output): void
    {
        if ($output) {
            $output->writeln('Get from buffer');
        }

        $rows = $this->bufferRepository->findBy(
            [],
            ['id' => 'ASC'],
            100
        );

        if ($output) {
            $output->writeln('Push to server');
        }

        foreach ($rows as $row) {
            try {
                if ($output) {
                    $output->writeln(sprintf(' => buffer id: %d', $row->getId()));
                }

                $this->boilerPush->push($row);
                $this->entityManager->remove($row);
                $this->entityManager->flush();
            } catch (Exception $e) {
                $row->setNbTry($row->getNbTry() + 1);
                $row->setLastError($e->getMessage());
                $this->entityManager->flush();
                throw $e;
            }
        }

        if ($output) {
            $output->writeln('End');
        }
    }
}
