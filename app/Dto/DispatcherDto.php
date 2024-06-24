<?php

namespace app\Dto;

class DispatcherDto
{
    protected $success;
    protected $message;
    protected $data;

    /**
     * DispatcherDto constructor.
     */
    public function __construct()
    {
        $this->success = false;
        $this->message = '';
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return json_decode($this->data);
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return get_object_vars($this);
    }


}
