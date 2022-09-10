<?php namespace img\unittest;

use img\ImagingException;
use img\io\WebpStreamWriter;
use io\IOException;
use io\streams\{MemoryOutputStream, OutputStream};
use unittest\actions\ExtensionAvailable;
use unittest\{Expect, Test};

/**
 * Tests writing WebP images
 */
#[Action(eval: '[new ExtensionAvailable("gd"), new ImageTypeSupport("WEBP")]')]
class WebpImageWriterTest extends AbstractImageWriterTest {

  #[Test, Expect(ImagingException::class)]
  public function write_error() {
    $this->image->saveTo(new WebpStreamWriter(new class() implements OutputStream {
      public function write($arg) { throw new IOException('Could not write: Intentional exception'); }
      public function flush() { }
      public function close() { }
    }));
  }

  #[Test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new WebpStreamWriter($s));
    $this->assertNotEquals('', $s->bytes());
  }
}