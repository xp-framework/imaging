<?php namespace img;
 
/**
 * Brush class
 *
 * @see xp://img.Image#setBrush
 */
class ImgBrush {
  public
    $image    = null,
    $style    = null;
    
  public
    $handle     = IMG_COLOR_STYLEDBRUSHED;
  
  /**
   * Constructor
   *
   * @param   img.Image an image object
   * @param   img.ImgStyle a style object
   */
  public function __construct($i, $s) {
    $this->image= $i;
    $this->style= $s;
    
  }
}