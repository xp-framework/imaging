<?php namespace img\unittest;

use img\Image;
use img\io\WebpStreamReader;
use io\streams\MemoryInputStream;
use unittest\actions\ExtensionAvailable;
use unittest\{Test, TestCase};

/**
 * Tests reading WebP images
 *
 * @see  img.io.WebPtreamReader
 */
#[Action(eval: '[new ExtensionAvailable("gd"), new ImageTypeSupport("WEBP")]')]
class WebpImageReaderTest extends TestCase {

  #[Test]
  public function read() {
    $s= new MemoryInputStream(base64_decode('UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA='));
    Image::loadFrom(new WebpStreamReader($s));
  }
}