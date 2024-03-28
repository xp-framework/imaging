<?php namespace img\unittest;

use img\{Color, Image};
use test\{After, Before};

abstract class AbstractImageWriterTest {
  protected $image;

  #[Before]
  public function setUp() {
    $this->image= Image::create(1, 1);
    $this->image->fill($this->image->allocate(new Color('#ffffff')));
  }

  #[After]
  public function tearDown() {
    unset($this->image);
  }
}