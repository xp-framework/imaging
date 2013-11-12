<?php namespace img\io;



/**
 * Reads images from URIs
 */
interface UriReader {

  /**
   * Read image
   *
   * @param   string uri
   * @return  resource
   * @throws  img.ImagingException
   */
  public function readImageFromUri($uri);
}
