<?php namespace img;

/**
 * Denotes a drawable object
 *
 * @see      xp://img.Image#draw
 * @purpose  Interface
 */
interface Drawable {

  /**
   * Draws this object onto an image
   *
   * @param   img.Image image
   * @return  var
   */
  public function draw($image);

}