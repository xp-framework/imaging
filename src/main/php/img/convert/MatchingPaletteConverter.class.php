<?php namespace img\convert;

use img\Image;

/**
 * Converts a truecolor image to a paletted image. Uses 
 * imagecolormatch() to get a better result
 *
 * @ext      gd
 * @see      php://imagecolormatch
 * @see      xp://img.convert.PaletteConverter
 * @purpose  Converter
 */
class MatchingPaletteConverter extends PaletteConverter {

  /**
   * Convert an image. Returns TRUE when successfull, FALSE if image is
   * not a truecolor image.
   *
   * @param   img.Image image
   * @return  bool
   * @throws  img.ImagingException
   */
  public function convert($image) { 
    if (!imageistruecolor($image->handle)) return false;
    
    $tmp= Image::create($image->getWidth(), $image->getHeight(), IMG_TRUECOLOR);
    $tmp->copyFrom($image);
    imagetruecolortopalette(
      $image->handle, 
      $this->dither, 
      $this->ncolors
    );
    imagecolormatch($tmp->handle, $image->handle);
    unset($tmp);
    return true;
  }
}