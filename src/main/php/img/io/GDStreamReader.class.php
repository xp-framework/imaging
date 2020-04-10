<?php namespace img\io;



/**
 * Reads GD from an image
 *
 * @ext  gd
 * @see  php://imagecreatefromgd
 * @see  xp://img.io.StreamReader
 */
class GDStreamReader extends StreamReader implements UriReader {

  /**
   * Read image
   *
   * @param   string uri
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromUri($uri) {
    if (false === ($r= imagecreatefromgd($uri))) {
      $e= new \img\ImagingException('Cannot read image from "'.$uri.'"');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
}