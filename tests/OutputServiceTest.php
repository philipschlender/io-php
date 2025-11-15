<?php

namespace Tests;

use Io\Enumerations\Mode;
use Io\Exceptions\IoException;
use Io\Models\Stream;
use Io\Models\StreamInterface;
use Io\Services\OutputService;
use Io\Services\OutputServiceInterface;

class OutputServiceTest extends TestCase
{
    protected OutputServiceInterface $outputService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->outputService = new class extends OutputService {
            /**
             * @throws IoException
             */
            protected function openStream(): StreamInterface
            {
                return new Stream('php://stdout', Mode::Write);
            }
        };
    }

    public function testWrite(): void
    {
        $this->markTestSkipped();
    }
}
