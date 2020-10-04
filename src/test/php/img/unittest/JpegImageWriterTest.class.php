<?php namespace img\unittest;

use img\ImagingException;
use img\io\JpegStreamWriter;
use io\streams\{MemoryOutputStream, OutputStream};
use io\{FileUtil, IOException};
use unittest\actions\ExtensionAvailable;
use unittest\{Expect, Test};

/**
 * Tests writing JPEG images
 */
#[Action(eval: '[new ExtensionAvailable("gd"), new ImageTypeSupport("JPEG")]')]
class JpegImageWriterTest extends AbstractImageWriterTest {

  #[Test, Expect(ImagingException::class)]
  public function write_error() {
    $this->image->saveTo(new JpegStreamWriter(new class() implements OutputStream {
      public function write($arg) { throw new IOException('Could not write: Intentional exception'); }
      public function flush() { }
      public function close() { }
    }));
  }

  #[Test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new JpegStreamWriter($s));
    $this->assertNotEquals('', $s->getBytes());
  }
}