<?php namespace img\io;

/**
 * Writes WebP to a stream
 *
 * @ext   gd
 * @see   https://www.php.net/manual/en/function.imagewebp.php
 * @see   img.io.StreamWriter
 */
class WebpStreamWriter extends StreamWriter {
  public $quality;
  
  /**
   * Constructor
   *
   * @param   io.Stream stream
   * @param   int quality default -1
   */
  public function __construct($stream, $quality= -1) {
    parent::__construct($stream);
    $this->quality= $quality;
  }

  /**
   * Output an image
   *
   * @param   resource handle
   * @return  bool
   */    
  public function output($handle) {
    if (imageistruecolor($handle)) return imagewebp($handle, null, $this->quality);

    // Prevent Fatal error: Paletter image not supported by webp
    $width= imagesx($handle);
    $height= imagesy($handle);
    $copy= imagecreatetruecolor($width, $height);
    imagecopy($copy, $handle, 0, 0, 0, 0, $width, $height);

    try {
      return imagewebp($copy, null, $this->quality);
    } finally {
      imagedestroy($copy);
    }
  }
}