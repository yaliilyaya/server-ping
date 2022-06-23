<?php

namespace App\Entity;

/**
 * @see DataTrait
 */
interface DataInterface
{
    public function getData(): ?array;

    /**
     * @param array $data
     * @return DataInterface
     */
    public function setData(array $data): DataInterface;

    /**
     * @param $field
     * @return mixed|null
     */
    public function __get($field);
    /**
     * @param $field
     * @param $value
     * @return void
     */
    public function __set($field, $value);

    /**
     * @param $field
     * @return bool
     */
    public function __isset($field);
}