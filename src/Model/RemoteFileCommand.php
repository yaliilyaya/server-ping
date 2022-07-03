<?php

namespace App\Model;

class RemoteFileCommand implements CommandInterface
{
    use CommandToStringTrait;

    /**
     * @var string
     */
    private $filePath;
    /**
     * @var string
     */
    private $tmpFilePath;
    /**
     * @var array
     */
    private $connectParam;

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
        $tmpFilePath = md5($filePath);
        $this->tmpFilePath = "/tmp/{$tmpFilePath}";
    }

    /**
     * @return string
     */
    public function getTmpFilePath(): string
    {
        return $this->tmpFilePath;
    }

    /**
     * @param string $tmpFilePath
     */
    public function setTmpFilePath(string $tmpFilePath): void
    {
        $this->tmpFilePath = $tmpFilePath;
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
        return [
            "scp",
            "-o",
            "ConnectTimeout=10",
            "-o",
            "StrictHostKeyChecking=no",
            "-o",
            "PasswordAuthentication=no",
            "{$this->connectParam[0]}@{$this->connectParam[1]}:{$this->filePath}",
            $this->tmpFilePath
        ];
    }
}