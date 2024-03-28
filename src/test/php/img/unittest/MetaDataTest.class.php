<?php namespace img\unittest;

use img\ImagingException;
use io\File;
use test\Assert;
use test\{Expect, Test, TestCase};

/**
 * Base class for EXIF- and IPTC-Data tests
 *
 * @see  xp://net.xp_framework.unittest.img.ExifDataTest
 * @see  xp://net.xp_framework.unittest.img.IptcDataTest
 */
abstract class MetaDataTest {

  /**
   * Returns a file for a classloader resource
   *
   * @param   string $name
   * @param   string $sub default NULL subpackage
   * @return  io.File
   */
  protected function resourceAsFile($name, $sub= null) {
    $package= typeof($this)->getPackage();
    $container= $sub ? $package->getPackage($sub) : $package;
    return $container->getResourceAsStream($name);
  }

  /**
   * Extract from file and return the instance
   *
   * @param   io.File $f
   * @return  lang.Generic the instance
   */
  protected abstract function extractFromFile(File $f);

  #[Test, Expect(ImagingException::class)]
  public function fromNonImageFile() {
    $this->extractFromFile(new File(__FILE__));
  }

  #[Test, Expect(ImagingException::class)]
  public function fromEmptyFile() {
    $this->extractFromFile($this->resourceAsFile('empty.jpg'));
  }
}