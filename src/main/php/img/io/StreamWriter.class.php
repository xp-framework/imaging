<?php namespace img\io;

use img\ImagingException;
use io\Channel;
use io\streams\OutputStream;
use lang\{IllegalArgumentException, Throwable};

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
  private $writer= null;

  /**
   * Constructor
   *
   * @param  io.streams.InputStream|io.Channel $arg
   * @throws lang.IllegalArgumentException when types are not met
   */
  public function __construct($arg) {
    if ($arg instanceof OutputStream) {
      $this->write($arg);
    } else if ($arg instanceof Channel) {
      $this->write($arg->out());
    } else {
      throw new IllegalArgumentException('Expected either an io.streams.OutputStream or an io.Channel, have '.type($arg)->getName());
    }
  }

  /** @param io.streams.OutputStream */
  private function write($stream) {
    $this->stream= $stream;

    // Use output buffering with a callback method to capture the 
    // image(gd|jpeg|png|...) functions' output.
    $this->writer= function($writer, $stream, $handle) {
      ob_start([$stream, 'write']);
      $r= $writer->output($handle);
      ob_end_clean();
      return $r;
    };
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