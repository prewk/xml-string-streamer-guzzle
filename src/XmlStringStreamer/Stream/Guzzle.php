<?php namespace Prewk\XmlStringStreamer\Stream;

use Prewk\XmlStringStreamer\StreamInterface;
use GuzzleHttp\Psr7;

class Guzzle implements StreamInterface
{
    private $stream;
    private $readBytes = 0;
    private $chunkSize;
    private $chunkCallback;
    private $url;

    public function __construct($url, $chunkSize = 1024, $chunkCallback = null)
    {
        $this->chunkSize = $chunkSize;
        $this->chunkCallback = $chunkCallback;
        $this->url = $url;
    }

    public function setGuzzleStream($stream)
    {
        $this->stream = $stream;
    }

    public function getChunk()
    {
        if (!isset($this->stream)) {
            $this->stream = Psr7\stream_for(fopen($this->url, "r"));
        }
        
        if (!$this->stream->eof()) {
        	$buffer = $this->stream->read($this->chunkSize);
            $this->readBytes += strlen($buffer);

            if (is_callable($this->chunkCallback)) {
                call_user_func_array($this->chunkCallback, array($buffer, $this->readBytes));
            }
            
            return $buffer;
        } else {
            return false;
        }
    }
}
