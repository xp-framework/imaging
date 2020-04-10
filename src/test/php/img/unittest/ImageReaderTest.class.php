<?php namespace img\unittest;

use img\{Image, ImagingException};
use img\io\{GifStreamReader, JpegStreamReader, PngStreamReader};
use io\{FileUtil, IOException};
use io\streams\{InputStream, MemoryInputStream};
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
    $s= newinstance(InputStream::class, [], [
      'read' => function($limit= 8192) { throw new IOException('Could not read: Intentional exception'); },
      'available' => function() { return 1; },
      'close' => function() { }
    ]);
    Image::loadFrom(new GifStreamReader($s));
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