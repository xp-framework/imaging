<?php namespace img\io;

use io\streams\OutputStream;
use io\Stream;
use io\File;
use lang\IllegalArgumentException;
use lang\Throwable;
use img\ImagingException;

/**
 * Writes to a stream
 *
 * @ext      gd
 * @test     xp://net.xp_framework.unittest.img.ImageWriterTest
 * @see      xp://img.io.ImageWriter
 * @see      xp://img.Image#saveTo
 */
abstract class StreamWriter implements ImageWriter {
  public $stream= null;
  private static $GD_USERSTREAMS_BUG= false;
  private $writer= null;

  static function __static() {
    self::$GD_USERSTREAMS_BUG= (
      version_compare(PHP_VERSION, '5.5.0RC1', '>=') && version_compare(PHP_VERSION, '5.5.1', '<') &&
      0 !== strncmp('WIN', PHP_OS, 3)
    );
  }

  /**
   * Constructor
   *
   * @param   var $arg either an io.streams.OutputStream, an io.File or an io.Stream (BC)
   * @throws  lang.IllegalArgumentException when types are not met
   */
  public function __construct($arg) {
    if ($arg instanceof OutputStream) {
      $this->write($arg);
    } else if ($arg instanceof File) {
      $this->write($arg->out());
    } else if ($arg instanceof Stream) {  // BC
      $this->stream= $arg;
      $this->writer= function($writer, $stream, $handle) {
        ob_start();
        if ($r= $writer->output($handle)) {
          $stream->open(STREAM_MODE_WRITE);
          $stream->write(ob_get_contents());
          $stream->close();
        }
        ob_end_clean();
        return $r;
      };
    } else {
      throw new IllegalArgumentException('Expected either an io.streams.OutputStream or an io.File, have '.type($arg)->getName());
    }
  }

  /** @param io.streams.OutputStream */
  private function write($stream) {
    $this->stream= $stream;
    if (self::$GD_USERSTREAMS_BUG) {
      $this->writer= function($writer, $stream, $handle) {
        ob_start();
        $r= $writer->output($handle) && $stream->write(ob_get_contents());
        ob_end_clean();
        return $r;
      };
    } else {

      // Use output buffering with a callback method to capture the 
      // image(gd|jpeg|png|...) functions' output.
      $this->writer= function($writer, $stream, $handle) {
        ob_start([$stream, 'write']);
        $r= $writer->output($handle);
        ob_end_clean();
        return $r;
      };
    }
  }

  /**
   * Output an image. Abstract method, overwrite in child
   * classes!
   *
   * @param   resource handle
   * @return  bool
   */    
  public abstract function output($handle);
  
  /**
   * Sets the image resource that is to be written
   *
   * @param   resource handle
   * @throws  img.ImagingException
   */
  public function setResource($handle) {
    $f= $this->writer;
    try {
      $r= $f($this, $this->stream, $handle);
      $this->stream->close();
    } catch (Throwable $e) {
      if (ob_get_level()) ob_clean();
      throw new ImagingException($e->getMessage());
    }
    if (!$r) throw new ImagingException('Could not write image');
  }
} 
