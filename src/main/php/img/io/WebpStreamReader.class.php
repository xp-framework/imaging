<?php namespace img\io;

use img\ImagingException;

/**
 * Reads WebP from an image
 *
 * @ext   gd
 * @see   https://www.php.net/manual/en/function.imagecreatefromwebp.php
 * @see   img.io.StreamReader
 */
class WebpStreamReader extends StreamReader implements UriReader {

  /**
   * Read image
   *
   * @param   string uri
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromUri($uri) {
    if (false === ($r= imagecreatefromwebp($uri))) {
      $e= new ImagingException('Cannot read image from "'.$uri.'"');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
}