<?php namespace img\unittest;

use img\ImagingException;
use io\streams\OutputStream;
use img\io\JpegStreamWriter;
use io\IOException;
use io\streams\MemoryOutputStream;
use io\FileUtil;

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
