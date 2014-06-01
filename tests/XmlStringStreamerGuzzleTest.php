<?php

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream; 
use Prewk\XmlStringStreamer\Parser; 

class XmlStringStreamerGuzzleTest extends PHPUnit_Framework_TestCase
{
    public function testLargeSimpleXml()
    {
        $url = "http://www.oskarthornblad.se/xml-test/500kb.xml";
        $nodes = 1197;
        $memoryUsageBefore = memory_get_usage(true);
        $streamProvider = new Stream\Guzzle($url, 1024);
        
        $counter = 0;
        $parser = new Parser\StringWalker();
        $streamer = new XmlStringStreamer($parser, $streamProvider);

        while ($node = $streamer->getNode()) {
            $counter++;
        }

        $memoryUsageAfter = memory_get_usage(true);

        $this->assertEquals($nodes, $counter, "There should be exactly $nodes nodes captured");
        $this->assertLessThan(500 * 1024, $memoryUsageAfter - $memoryUsageBefore, "Memory usage should not go higher than 500 KiB");
    }
}