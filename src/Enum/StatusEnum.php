<?php

namespace App\Enum;

class StatusEnum
{
    public const DEFAULT_TYPE = 'default';
    public const INFO_TYPE = 'info';
    public const SUCCESS_TYPE = 'success';
    public const WARNING_TYPE = 'warning';
    public const DANGER_TYPE = 'danger';
    public const ERROR_TYPE = 'warning';

    public $sortWeight = [
        self::DEFAULT_TYPE => 3,
        self::ERROR_TYPE => 2,
        self::SUCCESS_TYPE => 1
    ];

    /**
     * @param $status
     * @return int
     */
    public function getSortWeight($status): int
    {
        return $this->sortWeight[$status] ?? 0;
    }
}