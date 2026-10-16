<?php namespace img\unittest;

use img\Image;
use img\io\AvifStreamReader;
use io\streams\MemoryInputStream;
use test\Test;

#[ImageTypeSupport('avif')]
class AvifImageReaderTest {
  const BYTES= 'AAAAIGZ0eXBhdmlmAAAAAGF2aWZtaWYxbWlhZk1BMUIAAADybWV0YQAAAAAAAAAoaGRscgAAAAAAAAAAcGljdAAAAAAAAAAAAAAAAGxpYmF2aWYAAAAADnBpdG0AAAAAAAEAAAAeaWxvYwAAAABEAAABAAEAAAABAAABGgAAABoAAAAoaWluZgAAAAAAAQAAABppbmZlAgAAAAABAABhdjAxQ29sb3IAAAAAamlwcnAAAABLaXBjbwAAABRpc3BlAAAAAAAAAAEAAAABAAAAEHBpeGkAAAAAAwgICAAAAAxhdjFDgQAMAAAAABNjb2xybmNseAABAA0AAYAAAAAXaXBtYQAAAAAAAAABAAEEAQKDBAAAACJtZGF0EgAKCBgABggIaAwgMgwXgAkkkkQAAHlM1uhudWxsCg==';

  #[Test]
  public function read() {
    Image::loadFrom(new AvifStreamReader(new MemoryInputStream(base64_decode(self::BYTES))));
  }
}