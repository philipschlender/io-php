<?php

namespace Io\Services;

use Io\Exceptions\IoException;

interface OutputServiceInterface
{
    /**
     * @throws IoException
     */
    public function write(string $data): void;
}
