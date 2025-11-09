<?php

namespace Tests;

use Io\Enumerations\Mode;
use Io\Enumerations\Whence;
use Io\Exceptions\IoException;
use Io\Models\Stream;
use PHPUnit\Framework\Attributes\DataProvider;

class StreamTest extends TestCase
{
    #[DataProvider('dataProviderConstruct')]
    public function testConstruct(Mode $mode): void
    {
        $stream = new Stream('php://temp', $mode);

        $stream->close();

        $this->assertInstanceOf(Stream::class, $stream);
    }

    /**
     * @return array<int,array<string,Mode>>
     */
    public static function dataProviderConstruct(): array
    {
        return [
            [
                'mode' => Mode::Read,
            ],
            [
                'mode' => Mode::Write,
            ],
            [
                'mode' => Mode::Append,
            ],
        ];
    }

    public function testConstructFailedToOpen(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('Failed to open the path.');

        new Stream($this->fakerService->getDataTypeGenerator()->randomString(), Mode::Read);
    }

    #[DataProvider('dataProviderIsOpen')]
    public function testIsOpen(Mode $mode): void
    {
        $stream = new Stream('php://temp', $mode);

        $isOpen = $stream->isOpen();

        $stream->close();

        $this->assertTrue($isOpen);
    }

    /**
     * @return array<int,array<string,Mode>>
     */
    public static function dataProviderIsOpen(): array
    {
        return [
            [
                'mode' => Mode::Read,
            ],
            [
                'mode' => Mode::Write,
            ],
            [
                'mode' => Mode::Append,
            ],
        ];
    }

    #[DataProvider('dataProviderIsReadable')]
    public function testIsReadable(Mode $mode, bool $expectedIsReadable): void
    {
        $stream = new Stream('php://temp', $mode);

        $isReadable = $stream->isReadable();

        $stream->close();

        $this->assertEquals($expectedIsReadable, $isReadable);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderIsReadable(): array
    {
        return [
            [
                'mode' => Mode::Read,
                'expectedIsReadable' => true,
            ],
            [
                'mode' => Mode::Write,
                'expectedIsReadable' => true,
            ],
            [
                'mode' => Mode::Append,
                'expectedIsReadable' => true,
            ],
        ];
    }

    public function testIsReadableStreamClosed(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $isReadable = $stream->isReadable();

        $this->assertFalse($isReadable);
    }

    #[DataProvider('dataProviderRead')]
    public function testRead(string $data, ?int $length, string $expectedData): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->write($data);

        $stream->rewind();

        $dataRead = $stream->read($length);

        $stream->close();

        $this->assertEquals($expectedData, $dataRead);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderRead(): array
    {
        $data = 'abc';

        return [
            [
                'data' => $data,
                'length' => null,
                'expectedData' => $data,
            ],
            [
                'data' => $data,
                'length' => 1,
                'expectedData' => 'a',
            ],
        ];
    }

    public function testReadStreamNotReadable(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('The stream must be readable.');

        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $stream->read();
    }

    public function testReadInvalidLength(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('The length must be greater than or equal to 1.');

        $stream = new Stream('php://temp', Mode::Write);

        $stream->read(0);
    }

    #[DataProvider('dataProviderIsWritable')]
    public function testIsWritable(Mode $mode, bool $expectedIsWritable): void
    {
        $stream = new Stream('php://temp', $mode);

        $isWritable = $stream->isWritable();

        $stream->close();

        $this->assertEquals($expectedIsWritable, $isWritable);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderIsWritable(): array
    {
        return [
            [
                'mode' => Mode::Read,
                'expectedIsWritable' => false,
            ],
            [
                'mode' => Mode::Write,
                'expectedIsWritable' => true,
            ],
            [
                'mode' => Mode::Append,
                'expectedIsWritable' => true,
            ],
        ];
    }

    public function testIsWritableStreamClosed(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $isWritable = $stream->isWritable();

        $this->assertFalse($isWritable);
    }

    public function testWrite(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $data = $this->fakerService->getDataTypeGenerator()->randomString(32);

        $numberOfBytes = $stream->write($data);

        $stream->rewind();

        $dataRead = $stream->read();

        $stream->close();

        $this->assertEquals(32, $numberOfBytes);
        $this->assertEquals($data, $dataRead);
    }

    public function testWriteStreamNotWritable(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('The stream must be writable.');

        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $stream->write($this->fakerService->getDataTypeGenerator()->randomString());
    }

    public function testIsSeekable(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $isSeekable = $stream->isSeekable();

        $stream->close();

        $this->assertTrue($isSeekable);
    }

    public function testIsSeekableStreamClosed(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $isSeekable = $stream->isSeekable();

        $this->assertFalse($isSeekable);
    }

    #[DataProvider('dataProviderSeek')]
    public function testSeek(string $data, int $offset, Whence $whence, string $expectedData): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->write($data);

        $stream->seek($offset, $whence);

        $dataRead = $stream->read(1);

        $stream->close();

        $this->assertEquals($expectedData, $dataRead);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderSeek(): array
    {
        $data = 'abc';

        return [
            [
                'data' => $data,
                'offset' => 0,
                'whence' => Whence::Start,
                'expectedData' => 'a',
            ],
            [
                'data' => $data,
                'offset' => -2,
                'whence' => Whence::Current,
                'expectedData' => 'b',
            ],
            [
                'data' => $data,
                'offset' => -1,
                'whence' => Whence::End,
                'expectedData' => 'c',
            ],
        ];
    }

    public function testSeekStreamNotSeekable(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('The stream must be seekable.');

        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $stream->seek($this->fakerService->getDataTypeGenerator()->randomInteger(), Whence::Start);
    }

    public function testTell(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $position = $stream->tell();

        $stream->close();

        $this->assertEquals(0, $position);
    }

    public function testTellStreamClosed(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('The stream must be open.');

        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $stream->tell();
    }

    public function testEof(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->read();

        $eof = $stream->eof();

        $stream->close();

        $this->assertTrue($eof);
    }

    public function testEofStreamClosed(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('The stream must be open.');

        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $stream->eof();
    }

    public function testRewind(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->write($this->fakerService->getDataTypeGenerator()->randomString());

        $stream->rewind();

        $position = $stream->tell();

        $stream->close();

        $this->assertEquals(0, $position);
    }

    public function testRewindStreamClosed(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('The stream must be open.');

        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $stream->rewind();
    }

    public function testGetSize(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $size = $stream->getSize();

        $stream->close();

        $this->assertEquals(0, $size);
    }

    public function testGetSizeStreamClosed(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessage('The stream must be open.');

        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $stream->getSize();
    }

    public function testClose(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $this->assertFalse($stream->isOpen());
    }

    public function testCloseStreamClosed(): void
    {
        $stream = new Stream('php://temp', Mode::Write);

        $stream->close();

        $stream->close();

        $this->assertFalse($stream->isOpen());
    }
}
