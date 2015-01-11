<?php namespace img\unittest;

use lang\Runtime;
use unittest\TestCase;
use io\Stream;
use io\FileUtil;
use io\IOException;
use img\Image;
use img\io\GifStreamReader;
use img\io\JpegStreamReader;
use img\io\PngStreamReader;
use io\streams\MemoryInputStream;

/**
 * Tests reading images
 *
 * @see      xp://img.io.ImageReader
 */
#[@action(new \unittest\actions\ExtensionAvailable('gd'))]
class ImageReaderTest extends TestCase {

  #[@test, @expect('img.ImagingException')]
  public function readError() {
    $s= newinstance('io.streams.InputStream', [], [
      'read' => function($limit= 8192) { throw new IOException('Could not read: Intentional exception'); },
      'available' => function() { return 1; },
      'close' => function() { }
    ]);
    Image::loadFrom(new GifStreamReader($s));
  }

  #[@test, @expect('img.ImagingException')]
  public function readEmptyData() {
    $s= new MemoryInputStream('');
    Image::loadFrom(new PngStreamReader($s));
  }

  #[@test, @expect('img.ImagingException')]
  public function readMalformedData() {
    $s= new MemoryInputStream('@@MALFORMED@@');
    Image::loadFrom(new PngStreamReader($s));
  }

  #[@test, @expect('img.ImagingException')]
  public function readEmptyDataBC() {
    $s= new Stream();
    FileUtil::setContents($s, '');
    Image::loadFrom(new \img\io\StreamReader($s));
  }

  #[@test, @expect('img.ImagingException')]
  public function readMalformedDataBC() {
    $s= new Stream();
    FileUtil::setContents($s, '@@MALFORMED@@');
    Image::loadFrom(new \img\io\StreamReader($s));
  }
}
