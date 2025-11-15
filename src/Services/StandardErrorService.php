<?php

namespace Io\Services;

use Io\Enumerations\Mode;
use Io\Exceptions\IoException;
use Io\Models\Stream;
use Io\Models\StreamInterface;

class StandardErrorService extends OutputService
{
    /**
     * @throws IoException
     */
    protected function openStream(): StreamInterface
    {
        return new Stream('php://stderr', Mode::Write);
    }
}
