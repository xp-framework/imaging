<?php namespace img\unittest;

use img\Image;
use img\io\GifStreamReader;
use io\FileUtil;
use io\streams\MemoryInputStream;
use unittest\TestCase;

/**
 * Tests reading GIF images
 *
 * @see   xp://img.io.JpegStreamReader
 */
#[@action([
#  new \unittest\actions\ExtensionAvailable('gd'),
#  new ImageTypeSupport('GIF')
#])]
class GifImageReaderTest extends TestCase {

  #[@test]
  public function read() {
    $s= new MemoryInputStream(base64_decode('R0lGODdhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs='));
    Image::loadFrom(new GifStreamReader($s));
  }
}