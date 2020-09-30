xml-string-streamer-guzzle [![Build Status](https://travis-ci.org/prewk/xml-string-streamer-guzzle.svg?branch=master)](https://travis-ci.org/prewk/xml-string-streamer-guzzle) [![StyleCI](https://github.styleci.io/repos/19353197/shield?branch=master)](https://github.styleci.io/repos/19353197?branch=master)
==========================

Use with [xml-string-streamer](https://github.com/prewk/xml-string-streamer)

What is it?
-----------

Streams large XML files with low memory consumption, over HTTP using [Guzzle](http://guzzlephp.org).

Installing
----------

Run `composer require prewk/xml-string-streamer-guzzle` to install this package.

Examples
--------

````php
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

$url = "http://example.com/really-large-xml-file.xml";

$CHUNK_SIZE = 1024;
$stream = new Stream\Guzzle($url, $CHUNK_SIZE);
$parser = new Parser\StringWalker();

$streamer = new XmlStringStreamer($parser, $stream);

while ($node = $streamer->getNode()) {
	// ...
}
````

For more info, see the [xml-string-streamer](https://github.com/prewk/xml-string-streamer) repo.

Compatibility
-------------

* For PHP >= 5.5 with Guzzle 6 compatibility, use version `0.4.0`
* For PHP >= 7.2 with Guzzle 7 compatibility, use latest (`1.0.0` and onwards)
