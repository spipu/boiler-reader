<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Repository\BufferRepository;
use Doctrine\ORM\Mapping as ORM;
use Spipu\UiBundle\Entity\TimestampableTrait;
use Spipu\UiBundle\Entity\EntityInterface;

/**
 * @ORM\Entity(repositoryClass=BufferRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Buffer implements EntityInterface
{
    use TimestampableTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $data;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $nbTry = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lastError;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getTime(): ?int
    {
        return $this->time;
    }

    /**
     * @param int $time
     * @return $this
     */
    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataAsArray(): string
    {
        return print_r(json_decode($this->data, true), true);
    }

    /**
     * @return int
     */
    public function getNbTry(): int
    {
        return $this->nbTry;
    }

    /**
     * @param int $nbTry
     * @return $this
     */
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
