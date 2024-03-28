<?php namespace img\unittest;

use img\util\ExifData;
use lang\ElementNotFoundException;
use test\verify\Runtime;
use test\{Assert, Expect, Ignore, Test};

#[Runtime(extensions: ['exif'])]
class ExifDataTest extends MetaDataTest {

  /**
   * Extract from file and return the instance
   *
   * @param   io.File $f
   * @return  lang.Generic the instance
   */
  protected function extractFromFile(\io\File $f) {
    return ExifData::fromFile($f);
  }

  #[Test]
  public function defaultValueIfNotFound() {
    Assert::null(ExifData::fromFile($this->resourceAsFile('iptc-only.jpg'), null));
  }

  #[Test]
  public function emptyExifData() {
    Assert::equals(0, ExifData::$EMPTY->getWidth());
  }

  #[Test, Expect(ElementNotFoundException::class)]
  public function fromFileWithIptcOnly() {
    $this->extractFromFile($this->resourceAsFile('iptc-only.jpg'));
  }

  #[Test, Ignore('https://bugs.php.net/bug.php?id=72819')]
  public function fromFileWithExifAndIptc() {
    $i= $this->extractFromFile($this->resourceAsFile('exif-and-iptc.jpg'));
    Assert::equals(1, $i->getWidth());
    Assert::equals(1, $i->getHeight());
  }

  #[Test, Ignore('https://bugs.php.net/bug.php?id=72819')]
  public function fromFileWithExifOnly() {
    $i= $this->extractFromFile($this->resourceAsFile('exif-only.jpg'));
    Assert::equals(1, $i->getWidth());
    Assert::equals(1, $i->getHeight());
  }

  #[Test, Ignore('https://bugs.php.net/bug.php?id=72819')]
  public function exifSampleCanonIxus() {
    $i= $this->extractFromFile($this->resourceAsFile('canon-ixus.jpg', 'exif_org'));
    Assert::equals('1/350', $i->getExposureTime());
    Assert::equals('346/32', $i->getFocalLength());
    Assert::equals('Canon', $i->getMake());
    Assert::equals('f/4.0', $i->getApertureFNumber());
    Assert::equals(null, $i->getIsoSpeedRatings());
    Assert::equals(null, $i->getSoftware());
    Assert::equals(null, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(640, $i->getWidth());
    Assert::equals(480, $i->getHeight());
    Assert::equals('Canon DIGITAL IXUS', $i->getModel());
    Assert::equals('2001:06:09 15:17:32', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(2, $i->getMeteringMode());
    Assert::equals(0, $i->getFlash());
    Assert::false($i->flashUsed());
  }

  #[Test]
  public function exifSampleFujifilmDx10() {
    $i= $this->extractFromFile($this->resourceAsFile('fujifilm-dx10.jpg', 'exif_org'));
    Assert::equals(null, $i->getExposureTime());
    Assert::equals('58/10', $i->getFocalLength());
    Assert::equals('FUJIFILM', $i->getMake());
    Assert::equals('f/4.2', $i->getApertureFNumber());
    Assert::equals(150, $i->getIsoSpeedRatings());
    Assert::equals('Digital Camera DX-10 Ver1.00', $i->getSoftware());
    Assert::equals(2, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(1024, $i->getWidth());
    Assert::equals(768, $i->getHeight());
    Assert::equals('DX-10', $i->getModel());
    Assert::equals('2001:04:12 20:33:14', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(5, $i->getMeteringMode());
    Assert::equals(1, $i->getFlash());
    Assert::true($i->flashUsed());
  }

  #[Test]
  public function exifSampleFujifilmFinepix40i() {
    $i= $this->extractFromFile($this->resourceAsFile('fujifilm-finepix40i.jpg', 'exif_org'));
    Assert::equals(null, $i->getExposureTime());
    Assert::equals('870/100', $i->getFocalLength());
    Assert::equals('FUJIFILM', $i->getMake());
    Assert::equals('f/2.8', $i->getApertureFNumber());
    Assert::equals(200, $i->getIsoSpeedRatings());
    Assert::equals('Digital Camera FinePix40i Ver1.39', $i->getSoftware());
    Assert::equals(2, $i->getExposureProgram());
    Assert::equals(0, $i->getWhiteBalance());
    Assert::equals(600, $i->getWidth());
    Assert::equals(450, $i->getHeight());
    Assert::equals('FinePix40i', $i->getModel());
    Assert::equals('2000:08:04 18:22:57', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(5, $i->getMeteringMode());
    Assert::equals(1, $i->getFlash());
    Assert::true($i->flashUsed());
  }

  #[Test]
  public function exifSampleFujifilmMx1700() {
    $i= $this->extractFromFile($this->resourceAsFile('fujifilm-mx1700.jpg', 'exif_org'));
    Assert::equals(null, $i->getExposureTime());
    Assert::equals('99/10', $i->getFocalLength());
    Assert::equals('FUJIFILM', $i->getMake());
    Assert::equals('f/7.0', $i->getApertureFNumber());
    Assert::equals(125, $i->getIsoSpeedRatings());
    Assert::equals('Digital Camera MX-1700ZOOM Ver1.00', $i->getSoftware());
    Assert::equals(2, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(640, $i->getWidth());
    Assert::equals(480, $i->getHeight());
    Assert::equals('MX-1700ZOOM', $i->getModel());
    Assert::equals('2000:09:02 14:30:10', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(5, $i->getMeteringMode());
    Assert::equals(0, $i->getFlash());
    Assert::false($i->flashUsed());
  }

  #[Test, Ignore('Not reliable within XAR files, see issue #259')]
  public function exifSampleKodakDC210() {
    $i= $this->extractFromFile($this->resourceAsFile('kodak-dc210.jpg', 'exif_org'));
    Assert::equals('1/30', $i->getExposureTime());
    Assert::equals('44/10', $i->getFocalLength());
    Assert::equals('Eastman Kodak Company', $i->getMake());
    Assert::equals('f/4.0', $i->getApertureFNumber());
    Assert::equals(null, $i->getIsoSpeedRatings());
    Assert::equals(null, $i->getSoftware());
    Assert::equals(null, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(640, $i->getWidth());
    Assert::equals(480, $i->getHeight());
    Assert::equals('DC210 Zoom (V05.00)', $i->getModel());
    Assert::equals('2000:10:26 16:46:51', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(2, $i->getMeteringMode());
    Assert::equals(1, $i->getFlash());
    Assert::true($i->flashUsed());
  }

  #[Test]
  public function exifSampleKodakDC240() {
    $i= $this->extractFromFile($this->resourceAsFile('kodak-dc240.jpg', 'exif_org'));
    Assert::equals('1/30', $i->getExposureTime());
    Assert::equals('140/10', $i->getFocalLength());
    Assert::equals('EASTMAN KODAK COMPANY', $i->getMake());
    Assert::equals('f/4.0', $i->getApertureFNumber());
    Assert::equals(null, $i->getIsoSpeedRatings());
    Assert::equals(null, $i->getSoftware());
    Assert::equals(null, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(640, $i->getWidth());
    Assert::equals(480, $i->getHeight());
    Assert::equals('KODAK DC240 ZOOM DIGITAL CAMERA', $i->getModel());
    Assert::equals('1999:05:25 21:00:09', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(1, $i->getMeteringMode());
    Assert::equals(1, $i->getFlash());
    Assert::true($i->flashUsed());
  }

  #[Test]
  public function exifSampleNikonE950() {
    $i= $this->extractFromFile($this->resourceAsFile('nikon-e950.jpg', 'exif_org'));
    Assert::equals('10/770', $i->getExposureTime());
    Assert::equals('128/10', $i->getFocalLength());
    Assert::equals('NIKON', $i->getMake());
    Assert::equals('f/5.5', $i->getApertureFNumber());
    Assert::equals(80, $i->getIsoSpeedRatings());
    Assert::equals('v981-79', $i->getSoftware());
    Assert::equals(2, $i->getExposureProgram());
    Assert::equals(0, $i->getWhiteBalance());
    Assert::equals(800, $i->getWidth());
    Assert::equals(600, $i->getHeight());
    Assert::equals('E950', $i->getModel());
    Assert::equals('2001:04:06 11:51:40', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(5, $i->getMeteringMode());
    Assert::equals(0, $i->getFlash());
    Assert::false($i->flashUsed());
  }

  #[Test]
  public function exifSampleOlympusC960() {
    $i= $this->extractFromFile($this->resourceAsFile('olympus-c960.jpg', 'exif_org'));
    Assert::equals('1/345', $i->getExposureTime());
    Assert::equals('56/10', $i->getFocalLength());
    Assert::equals('OLYMPUS OPTICAL CO.,LTD', $i->getMake());
    Assert::equals('f/8.0', $i->getApertureFNumber());
    Assert::equals(125, $i->getIsoSpeedRatings());
    Assert::equals('OLYMPUS CAMEDIA Master', $i->getSoftware());
    Assert::equals(2, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(640, $i->getWidth());
    Assert::equals(480, $i->getHeight());
    Assert::equals('C960Z,D460Z', $i->getModel());
    Assert::equals('2000:11:07 10:41:43', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(5, $i->getMeteringMode());
    Assert::equals(0, $i->getFlash());
    Assert::false($i->flashUsed());
  }

  #[Test]
  public function exifSampleRicohrdc5300() {
    $i= $this->extractFromFile($this->resourceAsFile('ricoh-rdc5300.jpg', 'exif_org'));
    Assert::equals(null, $i->getExposureTime());
    Assert::equals('133/10', $i->getFocalLength());
    Assert::equals('RICOH', $i->getMake());
    Assert::equals('f/4.0', $i->getApertureFNumber());
    Assert::equals(null, $i->getIsoSpeedRatings());
    Assert::equals(null, $i->getSoftware());
    Assert::equals(null, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(896, $i->getWidth());
    Assert::equals(600, $i->getHeight());
    Assert::equals('RDC-5300', $i->getModel());
    Assert::equals('2000:05:31 21:50:40', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(null, $i->getMeteringMode());
    Assert::equals(1, $i->getFlash());
    Assert::true($i->flashUsed());
  }

  #[Test]
  public function exifSampleSanyoVpcg250() {
    $i= $this->extractFromFile($this->resourceAsFile('sanyo-vpcg250.jpg', 'exif_org'));
    Assert::equals('1/171', $i->getExposureTime());
    Assert::equals('60/10', $i->getFocalLength());
    Assert::equals('SANYO Electric Co.,Ltd.', $i->getMake());
    Assert::equals('f/8.0', $i->getApertureFNumber());
    Assert::equals(null, $i->getIsoSpeedRatings());
    Assert::equals('V06P-74', $i->getSoftware());
    Assert::equals(null, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(640, $i->getWidth());
    Assert::equals(480, $i->getHeight());
    Assert::equals('SR6', $i->getModel());
    Assert::equals('1998:01:01 00:00:00', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(2, $i->getMeteringMode());
    Assert::equals(1, $i->getFlash());
    Assert::true($i->flashUsed());
  }

  #[Test]
  public function exifSampleSanyovpcsx550() {
    $i= $this->extractFromFile($this->resourceAsFile('sanyo-vpcsx550.jpg', 'exif_org'));
    Assert::equals('10/483', $i->getExposureTime());
    Assert::equals('60/10', $i->getFocalLength());
    Assert::equals('SANYO Electric Co.,Ltd.', $i->getMake());
    Assert::equals('f/2.4', $i->getApertureFNumber());
    Assert::equals(400, $i->getIsoSpeedRatings());
    Assert::equals('V113p-73', $i->getSoftware());
    Assert::equals(null, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(640, $i->getWidth());
    Assert::equals(480, $i->getHeight());
    Assert::equals('SX113', $i->getModel());
    Assert::equals('2000:11:18 21:14:19', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(2, $i->getMeteringMode());
    Assert::equals(0, $i->getFlash());
    Assert::false($i->flashUsed());
  }

  #[Test]
  public function exifSampleSonyCybershot() {
    $i= $this->extractFromFile($this->resourceAsFile('sony-cybershot.jpg', 'exif_org'));
    Assert::equals('1/197', $i->getExposureTime());
    Assert::equals('216/10', $i->getFocalLength());
    Assert::equals('SONY', $i->getMake());
    Assert::equals('f/4.0', $i->getApertureFNumber());
    Assert::equals(100, $i->getIsoSpeedRatings());
    Assert::equals(null, $i->getSoftware());
    Assert::equals(2, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(640, $i->getWidth());
    Assert::equals(480, $i->getHeight());
    Assert::equals('CYBERSHOT', $i->getModel());
    Assert::equals('2000:09:30 10:59:45', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(2, $i->getMeteringMode());
    Assert::equals(0, $i->getFlash());
    Assert::false($i->flashUsed());
  }

  #[Test]
  public function exifSampleSonyD700() {
    $i= $this->extractFromFile($this->resourceAsFile('sony-d700.jpg', 'exif_org'));
    Assert::equals(null, $i->getExposureTime());
    Assert::equals(null, $i->getFocalLength());
    Assert::equals('SONY', $i->getMake());
    Assert::equals('f/2.4', $i->getApertureFNumber());
    Assert::equals(200, $i->getIsoSpeedRatings());
    Assert::equals(null, $i->getSoftware());
    Assert::equals(3, $i->getExposureProgram());
    Assert::equals(null, $i->getWhiteBalance());
    Assert::equals(672, $i->getWidth());
    Assert::equals(512, $i->getHeight());
    Assert::equals('DSC-D700', $i->getModel());
    Assert::equals('1998:12:01 14:22:36', $i->getDateTime()->toString('Y:m:d H:i:s'));
    Assert::equals(2, $i->getMeteringMode());
    Assert::equals(0, $i->getFlash());
    Assert::false($i->flashUsed());
  }
}