<?php

namespace Tests\Integration;

use GuzzleHttp\Psr7\NoSeekStream;
use PHPUnit\Framework\TestCase;
use Prewk\XmlStringStreamer\Stream\Guzzle;

class GuzzleTest extends TestCase
{
    /** @var string */
    private $firstChunk = 'abcde';

    /** @var string */
    private $secondChunk = 'fghij';

    /** @var string */
    private $source;

    protected function setUp()
    {
        parent::setUp();

        $this->source = "data:text/plain,{$this->firstChunk}{$this->secondChunk}";
    }

    /** @test */
    public function it_rewinds_a_stream()
    {
        $stream = new Guzzle($this->source, 5);

        $this->assertEquals($this->firstChunk, $stream->getChunk());
        $this->assertEquals($this->secondChunk, $stream->getChunk());

        $stream->rewind();

        $this->assertEquals($this->firstChunk, $stream->getChunk());
        $this->assertEquals($this->secondChunk, $stream->getChunk());
    }

    /** @test */
    public function it_throws_an_exception_if_a_stream_is_non_seekable()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Attempted to rewind an unseekable stream.');

        $stream = new Guzzle($this->source);

        $stream->setGuzzleStream(
            new NoSeekStream(\GuzzleHttp\Psr7\stream_for($this->source))
        );

        $stream->rewind();
    }

    /** @test */
    public function it_returns_true_on_seekable_stream()
    {
        $stream = new Guzzle($this->source);

        $this->assertTrue($stream->isSeekable());
    }

    /** @test */
    public function it_returns_false_on_non_seekable_stream()
    {
        $stream = new Guzzle($this->source);

        $stream->setGuzzleStream(
            new NoSeekStream(\GuzzleHttp\Psr7\stream_for($this->source))
        );

        $this->assertFalse($stream->isSeekable());
    }
}
