<?php namespace img\unittest;

use lang\XPClass;
use img\Image;
use img\Color;

/**
 * Tests writing images
 *
 * @see  xp://img.io.ImageWriter
 */
abstract class AbstractImageWriterTest extends \unittest\TestCase {
  protected $image= null;

  static function __static() {
    XPClass::forName('io.File');
  }

  /**
   * Setup this test. Creates a 1x1 pixel image filled with white.
   */
  public function setUp() {
    $this->image= Image::create(1, 1);
    $this->image->fill($this->image->allocate(new Color('#ffffff')));
  }

  /**
   * Tears down this test
   */
  public function tearDown() {
    unset($this->image);
  }
}
