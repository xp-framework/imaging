<?php namespace img\unittest;

use img\io\GifStreamWriter;
use io\IOException;
use io\streams\MemoryOutputStream;
use io\Stream;
use io\FileUtil;

/**
 * Tests writing GIF images
 */
#[@action([
#  new \unittest\actions\ExtensionAvailable('gd'),
#  new ImageTypeSupport('GIF')
#])]
class GifImageWriterTest extends AbstractImageWriterTest {

  #[@test, @expect('img.ImagingException')]
  public function write_error() {
    $this->image->saveTo(new GifStreamWriter(newinstance('io.streams.OutputStream', [], [
      'write' => function($arg) { throw new IOException('Could not write: Intentional exception'); },
      'flush' => function() { },
      'close' => function() { }
    ])));
  }

  #[@test]
  public function write() {
    $s= new MemoryOutputStream();
    $this->image->saveTo(new GifStreamWriter($s));
    $this->assertNotEquals('', $s->getBytes());
  }

  #[@test]
  public function write_bc() {
    $s= new Stream();
    $this->image->saveTo(new GifStreamWriter($s));
    $this->assertNotEquals('', FileUtil::getContents($s));
  }
}
