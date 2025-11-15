<?php

namespace Io\Services;

use Io\Exceptions\IoException;
use Io\Models\StreamInterface;

abstract class OutputService implements OutputServiceInterface
{
    protected StreamInterface $stream;

    /**
     * @throws IoException
     */
    public function __construct()
    {
        $this->stream = $this->openStream();
    }

    /**
     * @throws IoException
     */
    public function __destruct()
    {
        $this->stream->close();
    }

    /**
     * @throws IoException
     */
    public function write(string $data): void
    {
        $this->stream->write($data);
    }

    /**
     * @throws IoException
     */
    abstract protected function openStream(): StreamInterface;
}
