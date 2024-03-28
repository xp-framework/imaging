<?php namespace img\unittest;

use img\ImagingException;
use img\io\GifStreamWriter;
use io\IOException;
use io\streams\{MemoryOutputStream, OutputStream};
use test\{Assert, Expect, Test};

#[ImageTypeSupport('gif')]
class GifImageWriterTest extends AbstractImageWriterTest {

  #[Test, Expect(ImagingException::class)]
  public function write_error() {
    $this->image->saveTo(new GifStreamWriter(new class() implements OutputStream {
      public function write($arg) { throw new IOException('Could not write: Intentional exception'); }
      public function flush() { }
      public function close() { }
    }));
  }

  #[Test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new GifStreamWriter($s));
    Assert::notEquals('', $s->bytes());
  }
}