<?php namespace img\unittest;

use img\ImagingException;
use img\io\GifStreamWriter;
use io\{FileUtil, IOException};
use io\streams\{MemoryOutputStream, OutputStream};

/**
 * Tests writing GIF images
 */
#[@action([
#  new \unittest\actions\ExtensionAvailable('gd'),
#  new ImageTypeSupport('GIF')
#])]
class GifImageWriterTest extends AbstractImageWriterTest {

  #[@test, @expect(ImagingException::class)]
  public function write_error() {
    $this->image->saveTo(new GifStreamWriter(newinstance(OutputStream::class, [], [
      'write' => function($arg) { throw new IOException('Could not write: Intentional exception'); },
      'flush' => function() { },
      'close' => function() { }
    ])));
  }

  #[@test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new GifStreamWriter($s));
    $this->assertNotEquals('', $s->getBytes());
  }
}