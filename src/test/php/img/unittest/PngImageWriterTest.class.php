<?php namespace img\unittest;

use img\ImagingException;
use img\io\PngStreamWriter;
use io\OperationFailed;
use io\streams\{MemoryOutputStream, OutputStream};
use test\{Assert, Expect, Test};

#[ImageTypeSupport('png')]
class PngImageWriterTest extends AbstractImageWriterTest {

  /** @return string */
  protected function imageType() { return 'PNG'; }

  #[Test, Expect(ImagingException::class)]
  public function write_error() {
    $this->image->saveTo(new PngStreamWriter(new class() implements OutputStream {
      public function write($arg) { throw new OperationFailed('Could not write: Intentional exception'); }
      public function flush() { }
      public function close() { }
    }));
  }

  #[Test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new PngStreamWriter($s));
    Assert::notEquals('', $s->bytes());
  }
}