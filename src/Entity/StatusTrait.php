<?php


namespace App\Entity;


use App\Enum\StatusEnum;

/**
 * @see StatusInterface
 */
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
     * @return StatusInterface
     */
    public function setStatus(string $status): StatusInterface
    {
        $this->status = $status;
        return $this;
    }
}