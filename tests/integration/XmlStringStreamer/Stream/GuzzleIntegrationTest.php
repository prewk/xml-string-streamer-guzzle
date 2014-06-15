<?php

namespace Prewk\XmlStringStreamer\Stream;

use Prewk\XmlStringStreamer\Parser;
use Prewk\XmlStringStreamer;
use \PHPUnit_Framework_TestCase;

class GuzzleIntegrationTest extends PHPUnit_Framework_TestCase
{
    public function test_guzzle_stream_integrated()
    {
        $fileSize = 500 * 1024;
        $url = "http://www.oskarthornblad.se/xml-test/500kb.xml";
        $nodes = 1197;
        $memoryUsageBefore = memory_get_usage(true);
        $bufferSize = 1024;
        $streamProvider = new Guzzle($url, $bufferSize);
        
        $counter = 0;
        $parser = new Parser\StringWalker();
        $streamer = new XmlStringStreamer($parser, $streamProvider);

        while ($node = $streamer->getNode()) {
            $counter++;
        }

        $memoryUsageAfter = memory_get_usage(true);

        $this->assertEquals($nodes, $counter, "There should be exactly $nodes nodes captured");
        $this->assertLessThan($fileSize * 0.8, $memoryUsageAfter - $memoryUsageBefore, "Memory usage should not go higher than " . ceil($fileSize * 0.8 / 1024) . " KiB");
    }
}