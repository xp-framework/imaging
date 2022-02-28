<?php namespace img\unittest;

use img\ImagingException;
use img\io\PngStreamWriter;
use io\IOException;
use io\streams\{MemoryOutputStream, OutputStream};
use unittest\actions\ExtensionAvailable;
use unittest\{Expect, Test};

/**
 * Tests writing PNG images
 */
#[Action(eval: '[new ExtensionAvailable("gd"), new ImageTypeSupport("PNG")]')]
class PngImageWriterTest extends AbstractImageWriterTest {

  /** @return string */
  protected function imageType() { return 'PNG'; }

  #[Test, Expect(ImagingException::class)]
  public function write_error() {
    $this->image->saveTo(new PngStreamWriter(new class() implements OutputStream {
      public function write($arg) { throw new IOException('Could not write: Intentional exception'); }
      public function flush() { }
      public function close() { }
    }));
  }

  #[Test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new PngStreamWriter($s));
    $this->assertNotEquals('', $s->bytes());
  }
}