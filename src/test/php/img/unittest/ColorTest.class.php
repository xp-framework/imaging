<?php namespace img\unittest;

use img\Color;
use test\Assert;
use test\{Test, TestCase, Values};

class ColorTest {

  #[Test, Values(['#a7ff03', 'a7ff03', 'A7FF03', '#A7FF03'])]
  public function create_from_hex($value) {
    $c= new Color($value);
    Assert::equals(0xa7, $c->red);
    Assert::equals(0xff, $c->green);
    Assert::equals(0x03, $c->blue);
  }

  #[Test]
  public function create_from_three_rgb_values() {
    $c= new Color(1, 2, 3);
    Assert::equals(1, $c->red);
    Assert::equals(2, $c->green);
    Assert::equals(3, $c->blue);
  }

  #[Test]
  public function toHex_returns_lowercase_hex_with_leading_hash() {
    Assert::equals('#efefef', (new Color('#efefef'))->toHex());
  }

  #[Test]
  public function string_representation() {
    Assert::equals('img.Color@(239, 010, 007)', (new Color('#ef0a07'))->toString());
  }

  #[Test, Values([0xff000000, 0xffa7ff03, 0xffffffff])]
  public function argb_color_int($argb) {
    Assert::equals($argb, (new Color($argb))->intValue());
  }

  #[Test]
  public function argb_signed_32_bit() {
    Assert::equals(0xff464d32, (new Color(-12169934))->intValue());
  }
}