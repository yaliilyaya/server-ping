<?php

namespace App\Entity;


/**
 * @see DataInterface
 */
trait DataTrait
{
    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return DataInterface
     */
    public function setData(array $data): DataInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param $field
     * @return mixed|null
     */
    public function __get($field)
    {
        return $this->data[$field] ?? null;
    }

    /**
     * @param $field
     * @param $value
     * @return void
     */
    public function __set($field, $value)
    {
        $this->data[$field] = $value;
    }

    /**
     * @param $field
     * @return bool
     */
    public function __isset($field)
    {
        return isset($this->data[$field]);
    }
}