<?php namespace img\io;

use img\ImagingException;
use img\io\{SOFNSegment, IptcSegment, ExifSegment};
use img\util\{ExifData, ImageInfo, IptcData};
use lang\XPClass;
use util\Date;

/** Image meta data */
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
   * @param  string $type
   * @return img.io.Segment[]
   */
  public function segmentsOf($type) {
    $r= [];
    foreach ($this->segments as $segment) {
      if ($segment instanceof $type) $r[]= $segment;
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
    if (!($seg= $this->segmentsOf(SOFNSegment::class))) {
      throw new ImagingException('Cannot load image information from '.$this->source);
    }

    return [$seg[0]->width(), $seg[0]->height()];
  }

  /**
   * Returns an IptcData instance or NULL if this image does not contain 
   * IPTC Data
   * 
   * @param  ?util.TimeZone $tz
   * @return ?img.util.IptcData
   */
  public function iptcData($tz= null) {
    if (!($seg= $this->segmentsOf(IptcSegment::class))) return null;

    $data= new IptcData();
    $iptc= $seg[0]->rawData();

    // Parse creation date
    if (3 === sscanf($iptc['2#055'][0] ?? '', '%4d%2d%d', $year, $month, $day)) {
      if (isset($iptc['2#060'])) {
        sscanf($iptc['2#060'][0], '%2d%2d%2d', $hour, $minute, $second);
      } else {
        $hour= $minute= $second= 0;
      }
      $created= Date::create($year, $month, $day, $hour, $minute, $second, $tz);
    } else {
      $created= null;
    }

    return $data
      ->withTitle($iptc['2#005'][0] ?? null)
      ->withUrgency($iptc['2#010'][0] ?? null)
      ->withCategory($iptc['2#015'][0] ?? null)
      ->withSupplementalCategories($iptc['2#020'] ?? null)
      ->withKeywords($iptc['2#025'] ?? null)
      ->withSpecialInstructions($iptc['2#040'][0] ?? null)
      ->withDateCreated($created)
      ->withAuthor($iptc['2#080'][0] ?? null)
      ->withAuthorPosition($iptc['2#085'][0] ?? null)
      ->withCity($iptc['2#090'][0] ?? null)
      ->withState($iptc['2#095'][0] ?? null)
      ->withCountry($iptc['2#101'][0] ?? null)
      ->withOriginalTransmissionReference($iptc['2#103'][0] ?? null)
      ->withHeadline($iptc['2#105'][0] ?? null)
      ->withCredit($iptc['2#110'][0] ?? null)
      ->withSource($iptc['2#115'][0] ?? null)
      ->withCopyrightNotice($iptc['2#116'][0] ?? null)
      ->withCaption($iptc['2#120'][0] ?? null)
      ->withWriter($iptc['2#122'][0] ?? null)
    ;
  }

  /**
   * Lookup helper for exifData() method
   *
   * @param   [:var] exif
   * @param   string... $keys
   * @return  ?string value
   */
  protected static function lookup($exif, ... $keys) {
    foreach ($keys as $key) {
      if (isset($exif[$key])) return $exif[$key]['data'];
    }
    return null;
  }

  /**
   * Returns an ExifData instance or NULL if this image does not contain 
   * EXIF Data
   * 
   * @param  ?util.TimeZone $tz
   * @return ?img.util.ExifData
   */
  public function exifData($tz= null) {
    if (!($seg= $this->segmentsOf(ExifSegment::class))) return null;

    // Populate ExifData instance from ExifSegment's raw data
    with ($data= new ExifData(), $raw= $seg[0]->rawData()); {
      $data->withFileName($this->source);
      $data->withFileSize(-1);
      $data->withMimeType('image/jpeg');

      $data->withMake(null === ($l= self::lookup($raw, 'Make')) ? null : trim($l));
      $data->withModel(null === ($l= self::lookup($raw, 'Model')) ? null : trim($l));
      $data->withSoftware(null === ($l= self::lookup($raw, 'Software')) ? null : trim($l));

      $exif= $raw['Exif_IFD_Pointer']['data'];
      $data->withLensModel(null === ($l= self::lookup($exif, 'LensModel')) ? null : trim($l));

      if ($sof= $this->segmentsOf(SOFNSegment::class)) {
        $data->withWidth($sof[0]->width());
        $data->withHeight($sof[0]->height());
      } else {
        $data->withWidth(self::lookup($exif, 'ExifImageWidth'));
        $data->withHeight(self::lookup($exif, 'ExifImageLength'));
      }

      // Aperture is either a FNumber (use directly), otherwise calculate from value
      if (null === ($a= self::lookup($exif, 'FNumber'))) {
        if (null === ($a= self::lookup($exif, 'ApertureValue', 'MaxApertureValue'))) {
          $data->withApertureFNumber(null);
        } else {
          sscanf($a, '%d/%d', $n, $frac);
          $data->withApertureFNumber(sprintf('f/%.1F', exp($n / $frac * log(2) * 0.5)));
        }
      } else {
        sscanf($a, '%d/%d', $n, $frac);
        $data->withApertureFNumber(sprintf('f/%.1F', $n / $frac));
      }

      $data->withExposureTime(self::lookup($exif, 'ExposureTime'));
      $data->withExposureProgram(self::lookup($exif, 'ExposureProgram'));
      $data->withMeteringMode(self::lookup($exif, 'MeteringMode'));
      $data->withIsoSpeedRatings(self::lookup($exif, 'ISOSpeedRatings'));

      // Sometimes white balance is in MAKERNOTE - e.g. FUJIFILM's Finepix
      if (null !== ($w= self::lookup($exif, 'WhiteBalance'))) {
        $data->withWhiteBalance($w);
      } else if (isset($exif['MakerNote']) && null !== ($w= self::lookup($exif['MakerNote']['data'], 'WhiteBalance'))) {
        $data->withWhiteBalance($w);
      } else {
        $data->withWhiteBalance(null);
      }

      // Extract focal length. Some models store "80" as "80/1", rip off
      // the divisor "1" in this case.
      if (null !== ($l= self::lookup($exif, 'FocalLength'))) {
        sscanf($l, '%d/%d', $n, $frac);
        $data->withFocalLength(1 == $frac ? $n : $n.'/'.$frac);
      } else {
        $data->withFocalLength(null);
      }

      $data->withFlash(self::lookup($exif, 'Flash'));

      if (null !== ($date= self::lookup($exif, 'DateTimeOriginal', 'DateTimeDigitized', 'DateTime'))) {
        sscanf($date, '%d:%d:%d %d:%d:%d', $year, $month, $day, $hour, $minute, $second);
        $data->withDateTime(Date::create($year, $month, $day, $hour, $minute, $second, $tz));
      }

      if (null !== ($o= self::lookup($exif, 'Orientation'))) {
        $data->withOrientation($o);
      } else {
        $data->withOrientation(($data->width / $data->height) > 1.0
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