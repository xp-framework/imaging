<?php namespace img\unittest;

use img\Color;
use unittest\{Test, TestCase, Values};

class ColorTest extends TestCase {

  #[Test, Values(['#a7ff03', 'a7ff03', 'A7FF03', '#A7FF03'])]
  public function create_from_hex($value) {
    $c= new Color($value);
    $this->assertEquals(0xa7, $c->red);
    $this->assertEquals(0xff, $c->green);
    $this->assertEquals(0x03, $c->blue);
  }

  #[Test]
  public function create_from_three_rgb_values() {
    $c= new Color(1, 2, 3);
    $this->assertEquals(1, $c->red);
    $this->assertEquals(2, $c->green);
    $this->assertEquals(3, $c->blue);
  }

  #[Test]
  public function toHex_returns_lowercase_hex_with_leading_hash() {
    $this->assertEquals('#efefef', (new Color('#efefef'))->toHex());
  }

  #[Test]
  public function string_representation() {
    $this->assertEquals('img.Color@(239, 010, 007)', (new Color('#ef0a07'))->toString());
  }

  #[Test, Values([0xff000000, 0xffa7ff03, 0xffffffff])]
  public function argb_color_int($argb) {
    $this->assertEquals($argb, (new Color($argb))->intValue());
  }

  #[Test]
  public function argb_signed_32_bit() {
    $this->assertEquals(0xff464d32, (new Color(-12169934))->intValue());
  }
}