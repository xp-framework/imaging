<?php namespace img\unittest;

use img\util\IptcData;
use lang\ElementNotFoundException;
use test\Assert;
use test\{Expect, Test};

/**
 * TestCase for IptcData class
 *
 * @see  xp://net.xp_framework.unittest.img.MetaDataTest
 * @see  xp://img.util.IptcData
 */
class IptcDataTest extends MetaDataTest {

  /**
   * Extract from file and return the instance
   *
   * @param   io.File $f
   * @return  lang.Generic the instance
   */
  protected function extractFromFile(\io\File $f) {
    return IptcData::fromFile($f);
  }

  #[Test]
  public function defaultValueIfNotFound() {
    Assert::null(IptcData::fromFile($this->resourceAsFile('exif-only.jpg'), null));
  }

  #[Test]
  public function emptyIptcData() {
    Assert::equals('', IptcData::$EMPTY->getTitle());
  }

  #[Test, Expect(ElementNotFoundException::class)]
  public function fromFileWithoutIptc() {
    $this->extractFromFile($this->resourceAsFile('exif-only.jpg'));
  }

  #[Test]
  public function fromFileWithExifAndIptc() {
    $i= $this->extractFromFile($this->resourceAsFile('exif-and-iptc.jpg'));
    Assert::equals('Unittest Image', $i->getTitle());
  }

  #[Test]
  public function fromFile() {
    $i= $this->extractFromFile($this->resourceAsFile('iptc-only.jpg'));
    Assert::equals('Unittest Image', $i->getTitle());
  }
}