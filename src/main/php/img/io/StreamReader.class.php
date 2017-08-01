<?php namespace img\io;

use io\Stream;
use io\File;
use io\IOException;
use io\streams\InputStream;
use io\streams\Streams;
use lang\IllegalArgumentException;
use img\ImagingException;

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
  private static $GD_USERSTREAMS_BUG= false;
  private $reader= null;

  static function __static() {
    self::$GD_USERSTREAMS_BUG= (
      version_compare(PHP_VERSION, '5.5.0RC1', '>=') && version_compare(PHP_VERSION, '5.5.1', '<') &&
      0 !== strncmp('WIN', PHP_OS, 3)
    );
  }

  /**
   * Constructor
   *
   * @param   var $arg either an io.streams.InputStream, an io.File or an io.Stream (BC)
   * @throws  lang.IllegalArgumentException when types are not met
   */
  public function __construct($arg) {
    if ($arg instanceof InputStream) {
      $this->read($arg);
    } else if ($arg instanceof File) {
      $this->read($arg->in());
    } else if ($arg instanceof Stream) {  // BC
      $this->stream= $arg;
      $this->reader= function($reader, $stream) {
        $stream->open(STREAM_MODE_READ);
        $bytes= $stream->read($stream->size());
        $stream->close();
        return $reader->readImageFromString($bytes);
      };
    } else {
      throw new IllegalArgumentException('Expected either an io.streams.InputStream or an io.File, have '.typeof($this->stream)->getName());
    }
  }

  /** @param io.streams.InputStream */
  private function read($stream) {
    $this->stream= $stream;
    if ($this instanceof UriReader && !self::$GD_USERSTREAMS_BUG) {
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
