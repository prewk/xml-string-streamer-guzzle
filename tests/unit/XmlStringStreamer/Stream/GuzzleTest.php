<?php

namespace Prewk\XmlStringStreamer\Stream;

use \PHPUnit_Framework_TestCase;
use \Mockery;

class UniqueNodeTest extends PHPUnit_Framework_TestCase
{
    public function test_getChunk()
    {
        // Mock Guzzle stream
        $chunk1 = "1234567890";
        $chunk2 = "abcdefghij";
        $bufferSize = 10;
        $full = $chunk1 . $chunk2;

        $guzzleMock = Mockery::mock("\\GuzzleHttp\\Stream");
        $guzzleMock->shouldReceive("eof")
                   ->once()
                   ->andReturn(false);
        $guzzleMock->shouldReceive("read")
                   ->with($bufferSize)
                   ->once()
                   ->andReturn($chunk1);

        $guzzleMock->shouldReceive("eof")
                   ->once()
                   ->andReturn(false);
        $guzzleMock->shouldReceive("read")
                   ->with($bufferSize)
                   ->once()
                   ->andReturn($chunk2);

        $guzzleMock->shouldReceive("eof")
                   ->andReturn(true);

        $stream = new Guzzle("mock", $bufferSize);
        $stream->setGuzzleStream($guzzleMock);

        $this->assertEquals($stream->getChunk(), $chunk1, "First chunk received from the stream should be as expected");
        $this->assertEquals($stream->getChunk(), $chunk2, "Second chunk received from the stream should be as expected");
        $this->assertEquals($stream->getChunk(), false, "Third chunk received from the stream should be false");
    }
}