<?php namespace img\unittest;

use img\Image;
use img\io\GifStreamReader;
use io\streams\MemoryInputStream;
use test\Test;

#[ImageTypeSupport('gif')]
class GifImageReaderTest {
  const BYTES= 'R0lGODdhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs=';

  #[Test]
  public function read() {
    Image::loadFrom(new GifStreamReader(new MemoryInputStream(base64_decode(self::BYTES))));
  }
}