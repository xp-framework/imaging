<?php namespace img\io;

use img\ImagingException;
use img\util\{ExifData, ImageInfo, IptcData};
use lang\XPClass;
use util\Date;

/**
 * Image meta data
 * 
 */
class ImageMetaData {
  protected $source= null;
  protected $segments= [];

  /**
   * Sets source
   * 
   * @param  string source
   */
  public function setSource($source) {
    $this->source= $source;
  }

  /**
   * Adds a segment
   * 
   * @param  img.io.Segment
   */
  public function addSegment(Segment $segment) {
    $this->segments[]= $segment;
  }

  /**
   * Returns segments with a given name
   * 
   * @param  string name
   * @return img.io.Segment[]
   */
  public function allSegments() {
    return $this->segments;
  }

  /**
   * Returns segments with a given name
   * 
   * @param  string name
   * @return img.io.Segment[]
   */
  public function segmentsNamed($name) {
    $r= [];
    foreach ($this->segments as $segment) {
      if ($segment->marker === $name) $r[]= $segment;
    }
    return $r;
  }

  /**
   * Returns segments with a given class
   * 
   * @param  var type either a class name or an XPClass instance
   * @return img.io.Segment[]
   */
  public function segmentsOf($type) {
    $class= $type instanceof XPClass ? $type->getName() : $type;
    $r= [];
    foreach ($this->segments as $segment) {
      if (nameof($segment) === $class) $r[]= $segment;
    }
    return $r;
  }

  /**
   * Return image dimensions
   *
   * @return int[] an array with two integers - width and height
   * @throws img.ImagingException if no information can be extracted
   */
  public function imageDimensions() {
    if (!($seg= $this->segmentsOf('img.io.SOFNSegment'))) {
      throw new ImagingException('Cannot load image information from '.$this->source);
    }

    return [$seg[0]->width(), $seg[0]->height()];
  }

  /**
   * Returns an IptcData instance or NULL if this image does not contain 
   * IPTC Data
   * 
   * @return img.util.IptcData
   */
  public function iptcData() {
    if (!($seg= $this->segmentsOf('img.io.IptcSegment'))) return null;

    $data= new IptcData();
    $iptc= $seg[0]->rawData();

    // Parse creation date
    if (3 === sscanf($iptc['2#055'][0] ?? '', '%4d%2d%d', $year, $month, $day)) {
      $created= Date::create($year, $month, $day, 0, 0, 0);
    } else {
      $created= null;
    }

    $data->setTitle($iptc['2#005'][0] ?? null);
    $data->setUrgency($iptc['2#010'][0] ?? null);
    $data->setCategory($iptc['2#015'][0] ?? null);
    $data->setSupplementalCategories($iptc['2#020'] ?? null);
    $data->setKeywords($iptc['2#025'] ?? null);
    $data->setSpecialInstructions($iptc['2#040'][0] ?? null);
    $data->setDateCreated($created);
    $data->setAuthor($iptc['2#080'][0] ?? null);
    $data->setAuthorPosition($iptc['2#085'][0] ?? null);
    $data->setCity($iptc['2#090'][0] ?? null);
    $data->setState($iptc['2#095'][0] ?? null);
    $data->setCountry($iptc['2#101'][0] ?? null);
    $data->setOriginalTransmissionReference($iptc['2#103'][0] ?? null);
    $data->setHeadline($iptc['2#105'][0] ?? null);
    $data->setCredit($iptc['2#110'][0] ?? null);
    $data->setSource($iptc['2#115'][0] ?? null);
    $data->setCopyrightNotice($iptc['2#116'][0] ?? null);
    $data->setCaption($iptc['2#120'][0] ?? null);
    $data->setWriter($iptc['2#122'][0] ?? null);
    return $data;
  }

  /**
   * Lookup helper for exifData() method
   *
   * @param   [:var] exif
   * @param   string... key
   * @return  string value or NULL
   */
  protected static function lookup($exif) {
    for ($i= 1, $s= func_num_args(); $i < $s; $i++) {
      $key= func_get_arg($i);
      if (isset($exif[$key])) return $exif[$key]['data'];
    }
    return null;
  }

  /**
   * Returns an ExifData instance or NULL if this image does not contain 
   * EXIF Data
   * 
   * @return img.util.ExifData
   */
  public function exifData() {
    if (!($seg= $this->segmentsOf('img.io.ExifSegment'))) return null;

    // Populate ExifData instance from ExifSegment's raw data
    with ($data= new ExifData(), $raw= $seg[0]->rawData()); {
      $data->setFileName($this->source);
      $data->setFileSize(-1);
      $data->setMimeType('image/jpeg');

      $data->setMake(null === ($l= self::lookup($raw, 'Make')) ? null : trim($l));
      $data->setModel(null === ($l= self::lookup($raw, 'Model')) ? null : trim($l));
      $data->setSoftware(null === ($l= self::lookup($raw, 'Software')) ? null : trim($l));

      $exif= $raw['Exif_IFD_Pointer']['data'];

      if ($sof= $this->segmentsOf('img.io.SOFNSegment')) {
        $data->setWidth($sof[0]->width());
        $data->setHeight($sof[0]->height());
      } else {
        $data->setWidth(self::lookup($exif, 'ExifImageWidth'));
        $data->setHeight(self::lookup($exif, 'ExifImageLength'));
      }

      // Aperture is either a FNumber (use directly), otherwise calculate from value
      if (null === ($a= self::lookup($exif, 'FNumber'))) {
        if (null === ($a= self::lookup($exif, 'ApertureValue', 'MaxApertureValue'))) {
          $data->setApertureFNumber(null);
        } else {
          sscanf($a, '%d/%d', $n, $frac);
          $data->setApertureFNumber(sprintf('f/%.1F', exp($n / $frac * log(2) * 0.5)));
        }
      } else {
        sscanf($a, '%d/%d', $n, $frac);
        $data->setApertureFNumber(sprintf('f/%.1F', $n / $frac));
      }

      $data->setExposureTime(self::lookup($exif, 'ExposureTime'));
      $data->setExposureProgram(self::lookup($exif, 'ExposureProgram'));
      $data->setMeteringMode(self::lookup($exif, 'MeteringMode'));
      $data->setIsoSpeedRatings(self::lookup($exif, 'ISOSpeedRatings'));

      // Sometimes white balance is in MAKERNOTE - e.g. FUJIFILM's Finepix
      if (null !== ($w= self::lookup($exif, 'WhiteBalance'))) {
        $data->setWhiteBalance($w);
      } else if (isset($exif['MakerNote']) && null !== ($w= self::lookup($exif['MakerNote']['data'], 'WhiteBalance'))) {
        $data->setWhiteBalance($w);
      } else {
        $data->setWhiteBalance(null);
      }

      // Extract focal length. Some models store "80" as "80/1", rip off
      // the divisor "1" in this case.
      if (null !== ($l= self::lookup($exif, 'FocalLength'))) {
        sscanf($l, '%d/%d', $n, $frac);
        $data->setFocalLength(1 == $frac ? $n : $n.'/'.$frac);
      } else {
        $data->setFocalLength(null);
      }

      // Check for Flash and flashUsed keys
      if (null !== ($f= self::lookup($exif, 'Flash'))) {
        $data->setFlash($f);
      } else {
        $data->setFlash(null);
      }

      if (null !== ($date= self::lookup($exif, 'DateTimeOriginal', 'DateTimeDigitized', 'DateTime'))) {
        $t= sscanf($date, '%4d:%2d:%2d %2d:%2d:%2d');
        $data->setDateTime(new \util\Date(mktime($t[3], $t[4], $t[5], $t[1], $t[2], $t[0])));
      }

      if (null !== ($o= self::lookup($exif, 'Orientation'))) {
        $data->setOrientation($o);
      } else {
        $data->setOrientation(($data->width / $data->height) > 1.0
          ? 1   // normal
          : 5   // transpose
        );
      }

      return $data;
    }
  }

  /**
   * Test for equality
   *
   * @param  var cmp
   * @return bool
   */
  public function equals($cmp) {
    if (
      !$cmp instanceof self ||
      $cmp->source !== $this->source || 
      sizeof($this->segments) !== sizeof($this->segments)
    ) return false;

    foreach ($this->segments as $i => $segment) {
      if (!$segment->equals($cmp->segments[$i])) return false;
    }
    return true;
  }
}