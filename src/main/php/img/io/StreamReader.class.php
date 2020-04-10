<?php namespace img\io;

use img\ImagingException;
use io\{Channel, IOException};
use io\streams\{InputStream, Streams};
use lang\IllegalArgumentException;

/**
 * Read images from a stream
 *
 * @ext   gd
 * @test  xp://net.xp_framework.unittest.img.ImageReaderTest
 * @see   xp://img.io.ImageReader
 * @see   xp://img.Image#loadFrom
 */
class StreamReader implements ImageReader {
  public $stream= null;
  private $reader= null;

  /**
   * Constructor
   *
   * @param  io.streams.InputStream|io.Channel $arg
   * @throws lang.IllegalArgumentException when types are not met
   */
  public function __construct($arg) {
    if ($arg instanceof InputStream) {
      $this->read($arg);
    } else if ($arg instanceof Channel) {
      $this->read($arg->in());
    } else {
      throw new IllegalArgumentException('Expected either an io.streams.InputStream or an io.Channel, have '.typeof($this->stream)->getName());
    }
  }

  /** @param io.streams.InputStream */
  private function read($stream) {
    $this->stream= $stream;
    if ($this instanceof UriReader) {
      $this->reader= function($reader, $stream) {
        return $reader->readImageFromUri(Streams::readableUri($stream));
      };
    } else {
      $this->reader= function($reader, $stream) {
        $bytes= '';
        while ($stream->available() > 0) {
          $bytes.= $stream->read();
        }
        $stream->close();
        return $reader->readImageFromString($bytes);
      };
    }
  }

  /**
   * Read image via imagecreatefromstring()
   *
   * @param   string bytes
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromString($bytes) {
    if (false === ($r= imagecreatefromstring($bytes))) {
      $e= new ImagingException('Cannot read image');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
  
  /**
   * Retrieve an image resource
   *
   * @return  resource
   * @throws  img.ImagingException
   */
  public function getResource() {
    $f= $this->reader;
    try {
      return $f($this, $this->stream);
    } catch (IOException $e) {
      throw new ImagingException($e->getMessage());
    }
  }
} 