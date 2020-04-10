<?php namespace img\io;



/**
 * Reads WBMP from an image
 *
 * @ext  gd
 * @see  php://imagecreatefromwbmp
 * @see  xp://img.io.StreamReader
 */
class WBmpStreamReader extends StreamReader implements UriReader {

  /**
   * Read image
   *
   * @param   string uri
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromUri($uri) {
    if (false === ($r= imagecreatefromwbmp($uri))) {
      $e= new \img\ImagingException('Cannot read image from "'.$uri.'"');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
}