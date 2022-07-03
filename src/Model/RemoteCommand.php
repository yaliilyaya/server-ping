<?php

namespace App\Model;

class RemoteCommand implements CommandInterface
{
    /**
     * @var array
     */
    private $commandAttribute;
    /**
     * @var array
     */
    private $connectParam;

    /**
     * @return array
     */
    public function getCommandAttribute(): array
    {
        return $this->commandAttribute;
    }

    /**
     * @param array $commandAttribute
     */
    public function setCommandAttribute(array $commandAttribute): void
    {
        $this->commandAttribute = $commandAttribute;
    }

    /**
     * @return array
     */
    public function getConnectParam(): array
    {
        return $this->connectParam;
    }

    /**
     * @param array $connectParam
     */
    public function setConnectParam(array $connectParam): void
    {
        $this->connectParam = $connectParam;
    }


    /**
     * @return array
     */
    public function getCommand(): array
    {
        $remoteCommand = $this->generateRemoteCommand($this->commandAttribute);
        return $this->generateCommand($this->connectParam, $remoteCommand);
    }

    /**
     * @param array $command
     * @return string
     */
    private function generateRemoteCommand(array $command): string
    {
        return implode(" ", $command);
    }

    /**
     * @param array $connectParam
     * @param string $remoteCommand
     * @return string[]
     */
    private function generateCommand(array $connectParam, string $remoteCommand): array
    {
        return [
            "ssh",
            "{$connectParam[0]}@{$connectParam[1]}",
            "-o",
            "ConnectTimeout=10",
            "-o",
            "StrictHostKeyChecking=no",
            "-o",
            "PasswordAuthentication=no",
            "bash",
            "-c",
            "\"{$remoteCommand}\""
        ];
    }
}