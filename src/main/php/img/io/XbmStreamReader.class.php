<?php namespace img\io;



/**
 * Reads XBM from an image
 *
 * @ext  gd
 * @see  php://imagecreatefromxbm
 * @see  xp://img.io.StreamReader
 */
class XbmStreamReader extends StreamReader implements UriReader {

  /**
   * Read image
   *
   * @param   string uri
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromUri($uri) {
    if (false === ($r= imagecreatefromxbm($uri))) {
      $e= new \img\ImagingException('Cannot read image from "'.$uri.'"');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
}