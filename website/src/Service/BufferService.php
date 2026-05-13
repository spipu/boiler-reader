<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\BufferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Output\OutputInterface;

class BufferService
{
    private BoilerReader $boilerReader;
    private EntityManagerInterface $entityManager;
    private BufferRepository $bufferRepository;
    private BoilerPush $boilerPush;

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
     * @throws Exception
     */
    public function readDataAndSaveInBuffer(OutputInterface $output): void
    {
        $output->writeln('Read from boiler');

        $buffer = $this->boilerReader->read();

        $output->writeln('Save in buffer');

        $this->entityManager->persist($buffer);
        $this->entityManager->flush();

        $output->writeln(sprintf(' => buffer id: %d', $buffer->getId()));
    }

    /**
     * @throws Exception
     */
    public function pushDataFromBuffer(OutputInterface $output): void
    {
        $output->writeln('Get from buffer');

        $rows = $this->bufferRepository->findBy([], ['id' => 'ASC'], 100);

        $output->writeln('Push to server');

        foreach ($rows as $row) {
            try {
                $output->writeln(sprintf(' => buffer id: %d', $row->getId()));

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

        $output->writeln('End');
    }
}
