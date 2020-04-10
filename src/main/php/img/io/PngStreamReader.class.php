<?php namespace img\io;



/**
 * Reads PNG from an image
 *
 * @ext   gd
 * @see   php://imagecreatefrompng
 * @see   xp://img.io.StreamReader
 * @test  xp://net.xp_framework.unittest.img.PngImageReaderTest
 */
class PngStreamReader extends StreamReader implements UriReader {

  /**
   * Read image
   *
   * @param   string uri
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromUri($uri) {
    if (false === ($r= imagecreatefrompng($uri))) {
      $e= new \img\ImagingException('Cannot read image from "'.$uri.'"');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
}