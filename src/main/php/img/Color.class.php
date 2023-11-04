<?php namespace img;
 
/**
 * Color class
 *
 * @test  img.unittest.ColorTest
 * @see   img.Image
 */
class Color {
  public $red, $green, $blue;
  public $handle= null;
  
  /**
   * Creates a new color. Three forms are acceptable:
   *
   * ```php
   * new Color('#990000');    // from hex
   * new Color(255, 0, 255);  // from rgb components
   * new Color(0xffa7ff03);   // from ARGB color integer
   * ```
   *
   * @param  var... $args
   * @see    https://developer.android.com/reference/android/graphics/Color#color-ints
   */
  public function __construct(... $args) {
    if (3 === sizeof($args)) {
      list($this->red, $this->green, $this->blue)= $args;
    } else if (is_int($input= $args[0])) {
      $this->red= ($input >> 16) & 0xff;
      $this->green= ($input >> 8) & 0xff;
      $this->blue= $input & 0xff;
    } else {
      sscanf(ltrim($input, '#'), '%2x%2x%2x', $this->red, $this->green, $this->blue);
    }
  }

  /** Compresses this color into an ARGB integer */
  public function intValue(): int {
    return (255 & 0xff) << 24 | ($this->red & 0xff) << 16 | ($this->green & 0xff) << 8 | ($this->blue & 0xff);
  }
  
  /**
   * Get RGB value as hexadecimal string, e.g. `#990000`.
   *
   * @return string
   */
  public function toHex() {
    return sprintf('#%02x%02x%02x', $this->red, $this->green, $this->blue);
  }
  
  /**
   * Returns a string representation of this color
   *
   * @return  string
   */
  public function toString() {
    return sprintf(
      '%s@(%03d, %03d, %03d)',
      nameof($this),
      $this->red,
      $this->green,
      $this->blue
    );
  }
}