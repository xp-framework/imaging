<?php namespace img\unittest;

use img\io\PngStreamReader;
use img\{Image, ImagingException};
use io\IOException;
use io\streams\{InputStream, MemoryInputStream};
use test\{Expect, Test};

#[ImageTypeSupport('png')]
class ImageReaderTest {

  #[Test, Expect(ImagingException::class)]
  public function readError() {
    Image::loadFrom(new PngStreamReader(new class() implements InputStream {
      public function read($limit= 8192) { throw new IOException('Could not read: Intentional exception'); }
      public function available() { return 1; }
      public function close() { }
    }));
  }

  #[Test, Expect(ImagingException::class)]
  public function readEmptyData() {
    $s= new MemoryInputStream('');
    Image::loadFrom(new PngStreamReader($s));
  }

  #[Test, Expect(ImagingException::class)]
  public function readMalformedData() {
    $s= new MemoryInputStream('@@MALFORMED@@');
    Image::loadFrom(new PngStreamReader($s));
  }
}