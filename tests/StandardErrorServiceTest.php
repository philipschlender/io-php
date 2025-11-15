<?php

namespace Tests;

use Io\Services\OutputServiceInterface;
use Io\Services\StandardErrorService;

class StandardErrorServiceTest extends TestCase
{
    protected OutputServiceInterface $outputService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->outputService = new StandardErrorService();
    }

    public function testWrite(): void
    {
        $this->markTestSkipped();
    }
}
