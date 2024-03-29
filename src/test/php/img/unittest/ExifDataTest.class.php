<?php namespace img\unittest;

use img\util\ExifData;
use test\{Assert, Test, Values};

class ExifDataTest {

  #[Test]
  public function can_create() {
    new ExifData();
  }

  #[Test, Values([[0, false], [1, true], [2, false], [3, true]])]
  public function flashUsed($flash, $expected) {
    Assert::equals($expected, (new ExifData())->withFlash($flash)->flashUsed());
  }

  #[Test, Values([[0, true], [4, true], [5, false]])]
  public function isHorizontal($orientation, $expected) {
    Assert::equals($expected, (new ExifData())->withOrientation($orientation)->isHorizontal());
  }

  #[Test, Values([[0, false], [4, false], [5, true]])]
  public function isVertical($orientation, $expected) {
    Assert::equals($expected, (new ExifData())->withOrientation($orientation)->isVertical());
  }

  #[Test]
  public function string_representation() {
    Assert::notEquals('', (new ExifData())->toString());
  }
}