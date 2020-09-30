<?php

namespace Prewk\XmlStringStreamer\Stream;

use Exception;
use GuzzleHttp\Psr7;
use Prewk\XmlStringStreamer\StreamInterface;

class Guzzle implements StreamInterface
{
    /** @var StreamInterface */
    private $stream;

    /** @var int */
    private $readBytes = 0;

    /** @var int */
    private $chunkSize;

    /** @var callable|null */
    private $chunkCallback;

    public function __construct($url, $chunkSize = 1024, $chunkCallback = null)
    {
        $this->chunkSize = $chunkSize;
        $this->chunkCallback = $chunkCallback;
        $this->stream = new Psr7\CachingStream(
            Psr7\stream_for(fopen($url, 'r'))
        );
    }

    public function setGuzzleStream($stream)
    {
        $this->stream = $stream;
    }

    public function getChunk()
    {
        if (!$this->stream->eof()) {
            $buffer = $this->stream->read($this->chunkSize);
            $this->readBytes += strlen($buffer);

            if (is_callable($this->chunkCallback)) {
                call_user_func_array($this->chunkCallback, [$buffer, $this->readBytes]);
            }

            return $buffer;
        } else {
            return false;
        }
    }

    public function isSeekable()
    {
        return $this->stream->isSeekable();
    }

    public function rewind()
    {
        if ($this->isSeekable() === false) {
            throw new Exception('Attempted to rewind an unseekable stream.');
        }

        $this->readBytes = 0;
        $this->stream->rewind();
    }
}
