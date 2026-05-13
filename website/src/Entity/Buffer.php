<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Spipu\UiBundle\Entity\TimestampableTrait;
use Spipu\UiBundle\Entity\EntityInterface;

#[ORM\Entity(repositoryClass: 'App\Repository\BufferRepository')]
#[ORM\HasLifecycleCallbacks]
class Buffer implements EntityInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $time;

    #[ORM\Column(type: Types::TEXT)]
    private string $data;

    #[ORM\Column(type: Types::INTEGER)]
    private int $nbTry = 0;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lastError = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getDataAsArray(): string
    {
        return print_r(json_decode($this->data, true), true);
    }

    public function getNbTry(): int
    {
        return $this->nbTry;
    }

    public function setNbTry(int $nbTry): self
    {
        $this->nbTry = $nbTry;

        return $this;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function setLastError(?string $lastError): self
    {
        $this->lastError = $lastError;

        return $this;
    }
}
