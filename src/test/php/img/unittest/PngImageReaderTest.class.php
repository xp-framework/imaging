<?php namespace img\unittest;

use img\Image;
use img\io\PngStreamReader;
use io\FileUtil;
use io\streams\MemoryInputStream;
use unittest\actions\ExtensionAvailable;
use unittest\{Test, TestCase};

/**
 * Tests reading PNG images
 *
 * @see   xp://img.io.JpegStreamReader
 */
#[Action(eval: '[new ExtensionAvailable("gd"), new ImageTypeSupport("PNG")]')]
class PngImageReaderTest extends TestCase {

  #[Test]
  public function read() {
    $s= new MemoryInputStream(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX///+nxBvIAAAACklEQVQImWNgAAAAAgAB9HFkpgAAAABJRU5ErkJggg=='));
    Image::loadFrom(new PngStreamReader($s));
  }
}