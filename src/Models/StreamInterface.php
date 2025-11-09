<?php

namespace Io\Models;

use Io\Enumerations\Whence;
use Io\Exceptions\IoException;

interface StreamInterface
{
    public function isOpen(): bool;

    public function isReadable(): bool;

    /**
     * @throws IoException
     */
    public function read(?int $length = null): string;

    public function isWritable(): bool;

    /**
     * @throws IoException
     */
    public function write(string $data): int;

    public function isSeekable(): bool;

    /**
     * @throws IoException
     */
    public function seek(int $offset, Whence $whence): void;

    /**
     * @throws IoException
     */
    public function tell(): int;

    /**
     * @throws IoException
     */
    public function eof(): bool;

    /**
     * @throws IoException
     */
    public function rewind(): void;

    /**
     * @throws IoException
     */
    public function getSize(): int;

    /**
     * @throws IoException
     */
    public function close(): void;
}
