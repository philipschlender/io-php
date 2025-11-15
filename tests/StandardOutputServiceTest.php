<?php

namespace Tests;

use Io\Services\OutputServiceInterface;
use Io\Services\StandardOutputService;

class StandardOutputServiceTest extends TestCase
{
    protected OutputServiceInterface $outputService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->outputService = new StandardOutputService();
    }

    public function testWrite(): void
    {
        $this->markTestSkipped();
    }
}
