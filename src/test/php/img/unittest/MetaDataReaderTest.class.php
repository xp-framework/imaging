<?php namespace img\unittest;

use DOMDocument;
use img\ImagingException;
use img\io\{Segment, CommentSegment, MetaDataReader, SOFNSegment, XMPSegment, ExifSegment, IptcSegment};
use img\util\{ExifData, IptcData};
use test\{Assert, Before, Expect, Test, Values};
use util\{Date, TimeZone};

class MetaDataReaderTest {
  private $fixture;

  /**
   * Extract meta data from a given resource
   *
   * @param   string $name
   * @param   ?string $sub
   * @return  img.io.ImageMetaData
   */
  private function extractFromFile($name, $sub= null) {
    $package= typeof($this)->getPackage();
    $container= $sub ? $package->getPackage($sub) : $package;
    return $this->fixture->read($container->getResourceAsStream($name)->in(), $name);
  }

  /** @return iterable */
  private function timezones() {
    yield null;
    yield TimeZone::getByName('UTC');
    yield TimeZone::getByName('Europe/Berlin');
  }

  #[Before]
  public function fixture() {
    $this->fixture= new MetaDataReader();
  }

  #[Test, Expect(class: ImagingException::class, message: '/Could not find start of image/')]
  public function this_class_file() {
    $this->extractFromFile(basename(__FILE__));
  }

  #[Test, Expect(class: ImagingException::class, message: '/Could not find start of image/')]
  public function empty_file() {
    $this->extractFromFile('empty.jpg');
  }

  #[Test]
  public function all_segments() {
    $segments= $this->extractFromFile('1x1.jpg')->allSegments();

    Assert::instance('img.io.Segment[]', $segments);
    Assert::equals(9, sizeof($segments));
  }

  #[Test]
  public function segments_named_dqt() {
    $segments= $this->extractFromFile('1x1.jpg')->segmentsNamed('DQT');

    Assert::instance('img.io.Segment[]', $segments);
    Assert::equals(2, sizeof($segments));
  }

  #[Test]
  public function segment_named_sof0() {
    Assert::equals(
      [new SOFNSegment('SOF0', ['bits' => 8, 'height' => 1, 'width' => 1, 'channels' => 3])],
      $this->extractFromFile('1x1.jpg')->segmentsNamed('SOF0')
    );
  }

  #[Test]
  public function segment_of_sofn() {
    Assert::equals(
      [new SOFNSegment('SOF0', ['bits' => 8, 'height' => 1, 'width' => 1, 'channels' => 3])],
      $this->extractFromFile('1x1.jpg')->segmentsOf(SOFNSegment::class)
    );
  }

  #[Test]
  public function com_segment() {
    Assert::equals(
      [new CommentSegment('COM', 'Created with GIMP')],
      $this->extractFromFile('1x1.jpg')->segmentsOf(CommentSegment::class)
    );
  }

  #[Test]
  public function xmp_segment() {
    $segments= $this->extractFromFile('xmp.jpg')->segmentsOf(XMPSegment::class);

    Assert::instance('img.io.XMPSegment[]', $segments);
    Assert::matches('/^<.+/', $segments[0]->source);
    Assert::instance(DOMDocument::class, $segments[0]->document());
  }

  #[Test]
  public function dimensions_of_1x1_image() {
    Assert::equals([1, 1], $this->extractFromFile('1x1.jpg')->imageDimensions());
  }

  #[Test]
  public function dimensions_of_xmp_image() {
    Assert::equals([640, 480], $this->extractFromFile('canon-ixus.jpg', 'exif_org')->imageDimensions());
  }

  #[Test]
  public function no_exif_data_segments_in_1x1() {
    Assert::equals([], $this->extractFromFile('1x1.jpg')->segmentsOf(ExifSegment::class));
  }

  #[Test]
  public function no_iptc_data_segments_in_1x1() {
    Assert::equals([], $this->extractFromFile('1x1.jpg')->segmentsOf(IptcSegment::class));
  }

  #[Test]
  public function no_exif_data_in_1x1() {
    Assert::null($this->extractFromFile('1x1.jpg')->exifData());
  }

  #[Test]
  public function no_iptc_data_in_1x1() {
    Assert::null($this->extractFromFile('1x1.jpg')->iptcData());
  }

  #[Test]
  public function exif_data_segments() {
    Assert::instance(
      'img.io.ExifSegment[]',
      $this->extractFromFile('exif-only.jpg')->segmentsOf(ExifSegment::class)
    );
  }

  #[Test]
  public function iptc_data_segments() {
    Assert::instance(
      'img.io.IptcSegment[]',
      $this->extractFromFile('iptc-only.jpg')->segmentsOf(IptcSegment::class)
    );
  }

  #[Test]
  public function exif_and_iptc_data_segments() {
    $meta= $this->extractFromFile('exif-and-iptc.jpg');
    Assert::instance('img.io.ExifSegment[]', $meta->segmentsOf(ExifSegment::class));
    Assert::instance('img.io.IptcSegment[]', $meta->segmentsOf(IptcSegment::class));
  }

  #[Test]
  public function exif_dot_org_sample_CanonIxus() {
    Assert::equals(
      (new ExifData())
        ->withFileName('canon-ixus.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime('1/350')
        ->withExposureTime('1/350')
        ->withFocalLength('346/32')
        ->withMake('Canon')
        ->withApertureFNumber('f/4.0')
        ->withIsoSpeedRatings(null)
        ->withSoftware(null)
        ->withExposureProgram(null)
        ->withWhiteBalance(null)
        ->withWidth(640)
        ->withHeight(480)
        ->withModel('Canon DIGITAL IXUS')
        ->withDateTime(new Date('2001:06:09 15:17:32'))
        ->withMeteringMode(2)
        ->withFlash(0)
        ->withOrientation(1)
      ,
      $this->extractFromFile('canon-ixus.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_FujifilmDx10() {
    Assert::equals(
      (new ExifData())
        ->withFileName('fujifilm-dx10.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime(null)
        ->withFocalLength('58/10')
        ->withMake('FUJIFILM')
        ->withApertureFNumber('f/4.2')
        ->withIsoSpeedRatings(150)
        ->withSoftware('Digital Camera DX-10 Ver1.00')
        ->withExposureProgram(2)
        ->withWhiteBalance(null)
        ->withWidth(1024)
        ->withHeight(768)
        ->withModel('DX-10')
        ->withDateTime(new Date('2001:04:12 20:33:14'))
        ->withMeteringMode(5)
        ->withFlash(1)
        ->withOrientation(1)
      ,
      $this->extractFromFile('fujifilm-dx10.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_FujifilmFinepix40i() {
    Assert::equals(
      (new ExifData())
        ->withFileName('fujifilm-finepix40i.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime(null)
        ->withFocalLength('870/100')
        ->withMake('FUJIFILM')
        ->withApertureFNumber('f/2.8')
        ->withIsoSpeedRatings(200)
        ->withSoftware('Digital Camera FinePix40i Ver1.39')
        ->withExposureProgram(2)
        ->withWhiteBalance(0)
        ->withWidth(600)
        ->withHeight(450)
        ->withModel('FinePix40i')
        ->withDateTime(new Date('2000:08:04 18:22:57'))
        ->withMeteringMode(5)
        ->withFlash(1)
        ->withOrientation(1)
      ,
      $this->extractFromFile('fujifilm-finepix40i.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_FujifilmMx1700() {
    Assert::equals(
      (new ExifData())
        ->withFileName('fujifilm-mx1700.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime(null)
        ->withFocalLength('99/10')
        ->withMake('FUJIFILM')
        ->withApertureFNumber('f/7.0')
        ->withIsoSpeedRatings(125)
        ->withSoftware('Digital Camera MX-1700ZOOM Ver1.00')
        ->withExposureProgram(2)
        ->withWhiteBalance(null)
        ->withWidth(640)
        ->withHeight(480)
        ->withModel('MX-1700ZOOM')
        ->withDateTime(new Date('2000:09:02 14:30:10'))
        ->withMeteringMode(5)
        ->withFlash(0)
        ->withOrientation(1)
      ,
      $this->extractFromFile('fujifilm-mx1700.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_KodakDC210() {
    Assert::equals(
      (new ExifData())
        ->withFileName('kodak-dc210.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime('1/30')
        ->withFocalLength('44/10')
        ->withMake('Eastman Kodak Company')
        ->withApertureFNumber('f/4.0')
        ->withIsoSpeedRatings(null)
        ->withSoftware(null)
        ->withExposureProgram(null)
        ->withWhiteBalance(null)
        ->withWidth(640)
        ->withHeight(480)
        ->withModel('DC210 Zoom (V05.00)')
        ->withDateTime(new Date('2000:10:26 16:46:51'))
        ->withMeteringMode(2)
        ->withFlash(1)
        ->withOrientation(1)
      ,
      $this->extractFromFile('kodak-dc210.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_KodakDC240() {
    Assert::equals(
      (new ExifData())
        ->withFileName('kodak-dc240.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime('1/30')
        ->withFocalLength('140/10')
        ->withMake('EASTMAN KODAK COMPANY')
        ->withApertureFNumber('f/4.0')
        ->withIsoSpeedRatings(null)
        ->withSoftware(null)
        ->withExposureProgram(null)
        ->withWhiteBalance(null)
        ->withWidth(640)
        ->withHeight(480)
        ->withModel('KODAK DC240 ZOOM DIGITAL CAMERA')
        ->withDateTime(new Date('1999:05:25 21:00:09'))
        ->withMeteringMode(1)
        ->withFlash(1)
        ->withOrientation(1)
      ,
      $this->extractFromFile('kodak-dc240.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_NikonE950() {
    Assert::equals(
      (new ExifData())
        ->withFileName('nikon-e950.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime('10/770')
        ->withFocalLength('128/10')
        ->withMake('NIKON')
        ->withApertureFNumber('f/5.5')
        ->withIsoSpeedRatings(80)
        ->withSoftware('v981-79')
        ->withExposureProgram(2)
        ->withWhiteBalance(0)
        ->withWidth(800)
        ->withHeight(600)
        ->withModel('E950')
        ->withDateTime(new Date('2001:04:06 11:51:40'))
        ->withMeteringMode(5)
        ->withFlash(0)
        ->withOrientation(1)
      ,
      $this->extractFromFile('nikon-e950.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_OlympusC960() {
    Assert::equals(
      (new ExifData())
        ->withFileName('olympus-c960.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime('1/345')
        ->withFocalLength('56/10')
        ->withMake('OLYMPUS OPTICAL CO.,LTD')
        ->withApertureFNumber('f/8.0')
        ->withIsoSpeedRatings(125)
        ->withSoftware('OLYMPUS CAMEDIA Master')
        ->withExposureProgram(2)
        ->withWhiteBalance(null)
        ->withWidth(640)
        ->withHeight(480)
        ->withModel('C960Z,D460Z')
        ->withDateTime(new Date('2000:11:07 10:41:43'))
        ->withMeteringMode(5)
        ->withFlash(0)
        ->withOrientation(1)
      ,
      $this->extractFromFile('olympus-c960.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_Ricohrdc5300() {
    Assert::equals(
      (new ExifData())
        ->withFileName('ricoh-rdc5300.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime(null)
        ->withFocalLength('133/10')
        ->withMake('RICOH')
        ->withApertureFNumber('f/4.0')
        ->withIsoSpeedRatings(null)
        ->withSoftware(null)
        ->withExposureProgram(null)
        ->withWhiteBalance(null)
        ->withWidth(896)
        ->withHeight(600)
        ->withModel('RDC-5300')
        ->withDateTime(new Date('2000:05:31 21:50:40'))
        ->withMeteringMode(null)
        ->withFlash(1)
        ->withOrientation(1)
      ,
      $this->extractFromFile('ricoh-rdc5300.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_SanyoVpcg250() {
    Assert::equals(
      (new ExifData())
        ->withFileName('sanyo-vpcg250.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime('1/171')
        ->withFocalLength('60/10')
        ->withMake('SANYO Electric Co.,Ltd.')
        ->withApertureFNumber('f/8.0')
        ->withIsoSpeedRatings(null)
        ->withSoftware('V06P-74')
        ->withExposureProgram(null)
        ->withWhiteBalance(null)
        ->withWidth(640)
        ->withHeight(480)
        ->withModel('SR6')
        ->withDateTime(new Date('1998:01:01 00:00:00'))
        ->withMeteringMode(2)
        ->withFlash(1)
        ->withOrientation(1)
      ,
      $this->extractFromFile('sanyo-vpcg250.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_Sanyovpcsx550() {
    Assert::equals(
      (new ExifData())
        ->withFileName('sanyo-vpcsx550.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime('10/483')
        ->withFocalLength('60/10')
        ->withMake('SANYO Electric Co.,Ltd.')
        ->withApertureFNumber('f/2.4')
        ->withIsoSpeedRatings(400)
        ->withSoftware('V113p-73')
        ->withExposureProgram(null)
        ->withWhiteBalance(null)
        ->withWidth(640)
        ->withHeight(480)
        ->withModel('SX113')
        ->withDateTime(new Date('2000:11:18 21:14:19'))
        ->withMeteringMode(2)
        ->withFlash(0)
        ->withOrientation(1)
      ,
      $this->extractFromFile('sanyo-vpcsx550.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_SonyCybershot() {
    Assert::equals(
      (new ExifData())
        ->withFileName('sony-cybershot.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime('1/197')
        ->withFocalLength('216/10')
        ->withMake('SONY')
        ->withApertureFNumber('f/4.0')
        ->withIsoSpeedRatings(100)
        ->withSoftware(null)
        ->withExposureProgram(2)
        ->withWhiteBalance(null)
        ->withWidth(640)
        ->withHeight(480)
        ->withModel('CYBERSHOT')
        ->withDateTime(new Date('2000:09:30 10:59:45'))
        ->withMeteringMode(2)
        ->withFlash(0)
        ->withOrientation(1)
      ,
      $this->extractFromFile('sony-cybershot.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function exif_dot_org_sample_SonyD700() {
    Assert::equals(
      (new ExifData())
        ->withFileName('sony-d700.jpg')
        ->withFileSize(-1)
        ->withMimeType('image/jpeg')
        ->withExposureTime(null)
        ->withFocalLength(null)
        ->withMake('SONY')
        ->withApertureFNumber('f/2.4')
        ->withIsoSpeedRatings(200)
        ->withSoftware(null)
        ->withExposureProgram(3)
        ->withWhiteBalance(null)
        ->withWidth(672)
        ->withHeight(512)
        ->withModel('DSC-D700')
        ->withDateTime(new Date('1998:12:01 14:22:36'))
        ->withMeteringMode(2)
        ->withFlash(0)
        ->withOrientation(1)
      ,
      $this->extractFromFile('sony-d700.jpg', 'exif_org')->exifData()
    );
  }

  #[Test]
  public function detailed_iptc_data() {
    Assert::equals(
      (new IptcData())
        ->withTitle('Unittest Image')
        ->withUrgency(null)
        ->withCategory(null)
        ->withKeywords(null)
        ->withDateCreated(new Date('2011-12-07 14:08:24'))
        ->withAuthor(null)
        ->withAuthorPosition(null)
        ->withCity(null)
        ->withState(null)
        ->withCountry(null)
        ->withHeadline('Caption')
        ->withCredit('Provider')
        ->withSource('Source')
        ->withCopyrightNotice('Timm Friebe, 2012')
        ->withCaption('Description')
        ->withWriter('Timm')
        ->withSupplementalCategories(null)
        ->withSpecialInstructions(null)
        ->withOriginalTransmissionReference(null)
      ,
      $this->extractFromFile('detailed-iptc-embedded.jpg')->iptcData()
    );
  }

  #[Test]
  public function gps_data() {
    $exif= $this->extractFromFile('gps-embedded.jpg')->segmentsOf(ExifSegment::class)[0];
    Assert::equals(
      [
        'Version'      => '2/2/0/0',
        'Latitude'     => '48/1/59/1/54669/1000',    // 48° 59' 54,669" North
        'LatitudeRef'  => 'N',
        'Longitude'    => '8/1/23/1/10003/1000',     // 8° 23' 10,003" East
        'LongitudeRef' => 'E'
      ],
      [
        'Version'      => $exif->rawData('GPS_IFD_Pointer', 'GPSVersion'),
        'Latitude'     => $exif->rawData('GPS_IFD_Pointer', 'GPSLatitude'),
        'LatitudeRef'  => $exif->rawData('GPS_IFD_Pointer', 'GPSLatitudeRef'),
        'Longitude'    => $exif->rawData('GPS_IFD_Pointer', 'GPSLongitude'),
        'LongitudeRef' => $exif->rawData('GPS_IFD_Pointer', 'GPSLongitudeRef')
      ]
    );
  }

  #[Test, Values(from: 'timezones')]
  public function iptc_with_timezone($tz) {
    Assert::equals(
      new Date('2011-12-07 14:08:24', $tz),
      $this->extractFromFile('detailed-iptc-embedded.jpg')->iptcData($tz)->dateCreated
    );
  }

  #[Test, Values(from: 'timezones')]
  public function exif_with_timezone($tz) {
    Assert::equals(
      new Date('2001:06:09 15:17:32', $tz),
      $this->extractFromFile('canon-ixus.jpg', 'exif_org')->exifData($tz)->dateTime
    );
  }
}