<?php namespace img\unittest;

use img\Image;
use img\io\WebpStreamReader;
use io\streams\MemoryInputStream;
use test\Test;

#[ImageTypeSupport('webp')]
class WebpImageReaderTest {
  const BYTES= 'UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA=';

  #[Test]
  public function read() {
    Image::loadFrom(new WebpStreamReader(new MemoryInputStream(base64_decode(self::BYTES))));
  }
}