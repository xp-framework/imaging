<?php namespace img\unittest;

use img\Image;
use img\io\PngStreamReader;
use io\streams\MemoryInputStream;
use test\Test;

#[ImageTypeSupport('png')]
class PngImageReaderTest {
  const BYTES= 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX///+nxBvIAAAACklEQVQImWNgAAAAAgAB9HFkpgAAAABJRU5ErkJggg==';

  #[Test]
  public function read() {
    Image::loadFrom(new PngStreamReader(new MemoryInputStream(base64_decode(self::BYTES))));
  }
}