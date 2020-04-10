<?php namespace img\unittest;

use img\ImagingException;
use img\io\PngStreamWriter;
use io\streams\{MemoryOutputStream, OutputStream};
use io\{FileUtil, IOException};

/**
 * Tests writing PNG images
 */
#[@action([
#  new \unittest\actions\ExtensionAvailable('gd'),
#  new ImageTypeSupport('PNG')
#])]
class PngImageWriterTest extends AbstractImageWriterTest {

  /** @return string */
  protected function imageType() { return 'PNG'; }

  #[@test, @expect(ImagingException::class)]
  public function write_error() {
    $this->image->saveTo(new PngStreamWriter(new class() implements OutputStream {
      public function write($arg) { throw new IOException('Could not write: Intentional exception'); }
      public function flush() { }
      public function close() { }
    }));
  }

  #[@test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new PngStreamWriter($s));
    $this->assertNotEquals('', $s->getBytes());
  }
}