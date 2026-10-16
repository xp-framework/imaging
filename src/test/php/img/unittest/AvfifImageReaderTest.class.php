<?php namespace img\unittest;

use img\Image;
use img\io\WebpStreamReader;
use io\streams\MemoryInputStream;
use test\Test;

#[ImageTypeSupport('avif')]
class AvfifImageReaderTest {
  const BYTES= 'aW1nLkltYWdlIHsKICB3aWR0aCA9PiAxCiAgaGVpZ2h0ID0+IDEKICBwYWxldHRlID0+IFtdCiAgaGFuZGxlID0+IEdkSW1hZ2UgewogIH0KfQo=';

  #[Test]
  public function read() {
    Image::loadFrom(new AvfifStreamReader(new MemoryInputStream(base64_decode(self::BYTES))));
  }
}