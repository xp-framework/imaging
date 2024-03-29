<?php namespace img\unittest;

use img\util\IptcData;
use test\{Assert, Test};

class IptcDataTest {

  #[Test]
  public function can_create() {
    new IptcData();
  }

  #[Test]
  public function string_representation() {
    Assert::notEquals('', (new IptcData())->toString());
  }
}