<?php namespace img\convert;

use img\ImagingException;


/**
 * Converter interface
 *
 * @see      xp://img.Image#convertTo
 * @purpose  Interface
 */
interface ImageConverter {

  /**
   * Convert an image. Note: This changes the given image!
   *
   * @param   img.Image image
   * @return  bool
   * @throws  img.ImagingException
   */
  public function convert($image);
}