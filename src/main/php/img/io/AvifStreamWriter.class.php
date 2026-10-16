<?php namespace img\io;

/**
 * Writes Avif to a stream
 *
 * @ext   gd
 * @see   https://www.php.net/manual/en/function.imageavif.php
 * @see   img.io.StreamWriter
 */
class AvifStreamWriter extends StreamWriter {
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
    if (imageistruecolor($handle)) return imageavif($handle, null, $this->quality);

    // Prevent "imageavif(): avif error - avif doesn't support palette images"
    $width= imagesx($handle);
    $height= imagesy($handle);
    $copy= imagecreatetruecolor($width, $height);
    imagecopy($copy, $handle, 0, 0, 0, 0, $width, $height);

    try {
      return imageavif($copy, null, $this->quality);
    } finally {
      PHP_VERSION_ID <= 80000 && imagedestroy($copy);
    }
  }
}