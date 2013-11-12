<?php namespace img\io;



/**
 * Reads GD2 from an image
 *
 * @ext  gd
 * @see  php://imagecreatefromgd2
 * @see  xp://img.io.StreamReader
 */
class GD2StreamReader extends StreamReader implements UriReader {

  /**
   * Read image
   *
   * @param   string uri
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromUri($uri) {
    if (false === ($r= imagecreatefromgd2($uri))) {
      $e= new \img\ImagingException('Cannot read image from "'.$uri.'"');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
}
