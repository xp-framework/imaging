<?php namespace img\io;

use img\ImagingException;

/**
 * Reads Avif from an image
 *
 * @ext   gd
 * @see   https://www.php.net/manual/en/function.imagecreatefromavif.php
 * @see   img.io.StreamReader
 */
class AvifStreamReader extends StreamReader implements UriReader {

  /**
   * Read image
   *
   * @param   string uri
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromUri($uri) {
    if (false === ($r= imagecreatefromavif($uri))) {
      $e= new ImagingException('Cannot read image from "'.$uri.'"');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
}