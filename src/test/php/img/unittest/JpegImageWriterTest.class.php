<?php namespace img\unittest;

use img\ImagingException;
use img\io\JpegStreamWriter;
use io\{FileUtil, IOException};
use io\streams\{MemoryOutputStream, OutputStream};

/**
 * Tests writing JPEG images
 */
#[@action([
#  new \unittest\actions\ExtensionAvailable('gd'),
#  new ImageTypeSupport('JPEG')
#])]
class JpegImageWriterTest extends AbstractImageWriterTest {

  #[@test, @expect(ImagingException::class)]
  public function write_error() {
    $this->image->saveTo(new JpegStreamWriter(newinstance(OutputStream::class, [], [
      'write' => function($arg) { throw new IOException('Could not write: Intentional exception'); },
      'flush' => function() { },
      'close' => function() { }
    ])));
  }

  #[@test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new JpegStreamWriter($s));
    $this->assertNotEquals('', $s->getBytes());
  }
}