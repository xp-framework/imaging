<?php namespace img\unittest;

use img\io\{GifStreamReader, JpegStreamReader, PngStreamReader};
use img\{Image, ImagingException};
use io\streams\{InputStream, MemoryInputStream};
use io\{FileUtil, IOException};
use lang\Runtime;
use unittest\actions\ExtensionAvailable;
use unittest\{Expect, Test, TestCase};

/**
 * Tests reading images
 *
 * @see      xp://img.io.ImageReader
 */
#[Action(eval: 'new ExtensionAvailable("gd")')]
class ImageReaderTest extends TestCase {

  #[Test, Expect(ImagingException::class)]
  public function readError() {
    Image::loadFrom(new GifStreamReader(new class() implements InputStream {
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