<?php namespace img\io;

use io\streams\OutputStream;
use io\Stream;


/**
 * Writes to a stream
 *
 * @ext      gd
 * @test     xp://net.xp_framework.unittest.img.ImageWriterTest
 * @see      xp://img.io.ImageWriter
 * @see      xp://img.Image#saveTo
 * @purpose  Abstract base class
 */
abstract class StreamWriter extends \lang\Object implements ImageWriter {
  public $stream= null;
  protected static $GD_USERSTREAMS_BUG= false;
  protected $writer= null;

  static function __static() {
    self::$GD_USERSTREAMS_BUG= (
      version_compare(PHP_VERSION, '5.5.0RC1', '>=') && version_compare(PHP_VERSION, '5.5.1', '<') &&
      0 !== strncmp('WIN', PHP_OS, 3)
    );
  }

  /**
   * Constructor
   *
   * @param   var stream either an io.streams.OutputStream or an io.Stream (BC)
   * @throws  lang.IllegalArgumentException when types are not met
   */
  public function __construct($stream) {
    $this->stream= deref($stream);
    if ($this->stream instanceof OutputStream) {
      // Already open
    } else if ($this->stream instanceof Stream) {
      $this->stream->open(STREAM_MODE_WRITE);
    } else {
      throw new \lang\IllegalArgumentException('Expected either an io.streams.OutputStream or an io.Stream, have '.\xp::typeOf($this->stream));
    }

    if (self::$GD_USERSTREAMS_BUG) {
      $this->writer= function($writer, $stream, $handle) {
        ob_start();
        $r= $writer->output($handle);
        if ($r) {
          $stream->write(ob_get_contents());
        }
        ob_end_clean();
        return $r;
      };
    } else {

      // Use output buffering with a callback method to capture the 
      // image(gd|jpeg|png|...) functions' output.
      $this->writer= function($writer, $stream, $handle) {
        ob_start(function($data) use($stream) { $stream->write($data); });
        $r= $writer->output($handle);
        ob_end_flush();
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
    try {
      $r= call_user_func($this->writer, $this, $this->stream, $handle);
      $this->stream->close();
    } catch (\lang\Throwable $e) {
      ob_clean();
      throw new \img\ImagingException($e->getMessage());
    }
    if (!$r) throw new \img\ImagingException('Could not write image');
  }
} 
