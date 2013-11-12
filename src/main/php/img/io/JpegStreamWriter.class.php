<?php namespace img\io;



/**
 * Writes JPEG to a stream
 *
 * @ext   gd
 * @see   php://imagejpeg
 * @see   xp://img.io.StreamWriter
 * @test  xp://net.xp_framework.unittest.img.JpegImageWriterTest
 */
class JpegStreamWriter extends StreamWriter {
  public $quality  = 0;
  
  /**
   * Constructor
   *
   * @param   io.Stream stream
   * @param   int quality default 75
   */
  public function __construct($stream, $quality= 75) {
    parent::__construct($stream);
    $this->quality= $quality;
  }

  /**
   * Output an image
   *
   * @param   resource handle
   * @return  bool
   */    
  public function output($handle) {
    return imagejpeg($handle, null, $this->quality);
  }
}
