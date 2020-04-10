<?php namespace img\unittest;

use img\io\{GifStreamReader, JpegStreamReader, PngStreamReader};
use img\{Image, ImagingException};
use io\streams\{InputStream, MemoryInputStream};
use io\{FileUtil, IOException};
use lang\Runtime;
use unittest\TestCase;

/**
 * Tests reading images
 *
 * @see      xp://img.io.ImageReader
 */
#[@action(new \unittest\actions\ExtensionAvailable('gd'))]
class ImageReaderTest extends TestCase {

  #[@test, @expect(ImagingException::class)]
  public function readError() {
    Image::loadFrom(new GifStreamReader(new class() implements InputStream {
      public function read($limit= 8192) { throw new IOException('Could not read: Intentional exception'); }
      public function available() { return 1; }
      public function close() { }
    }));
  }

  #[@test, @expect(ImagingException::class)]
  public function readEmptyData() {
    $s= new MemoryInputStream('');
    Image::loadFrom(new PngStreamReader($s));
  }

  #[@test, @expect(ImagingException::class)]
  public function readMalformedData() {
    $s= new MemoryInputStream('@@MALFORMED@@');
    Image::loadFrom(new PngStreamReader($s));
  }
}