<?php namespace img\unittest;

use img\ImagingException;
use img\io\JpegStreamWriter;
use io\streams\{MemoryOutputStream, OutputStream};
use io\{FileUtil, IOException};

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
    $this->image->saveTo(new JpegStreamWriter(new class() implements OutputStream {
      public function write($arg) { throw new IOException('Could not write: Intentional exception'); }
      public function flush() { }
      public function close() { }
    }));
  }

  #[@test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new JpegStreamWriter($s));
    $this->assertNotEquals('', $s->getBytes());
  }
}