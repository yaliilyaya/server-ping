<?php


namespace App\Entity;


use App\Enum\StatusEnum;

trait StatusTrait
{
    /**
     * @var string
     * @see StatusEnum
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}