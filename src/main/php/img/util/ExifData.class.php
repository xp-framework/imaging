<?php namespace img\util;

use lang\Value;
use util\{Date, Objects};

/**
 * EXIF headers from JPEG or TIFF
 *
 * @test  net.xp_framework.unittest.img.ExifDataTest
 */
class ExifData implements Value {
  public static $EMPTY;

  public
    $height           = 0,
    $width            = 0,
    $make             = '',
    $model            = '',
    $lensModel        = '',
    $flash            = 0,
    $orientation      = 0,
    $fileName         = '',
    $fileSize         = 0,
    $mimeType         = '',
    $dateTime         = null,
    $apertureFNumber  = '',
    $software         = '',
    $exposureTime     = '',
    $exposureProgram  = 0,
    $whitebalance     = 0,
    $meteringMode     = 0,
    $isoSpeedRatings  = 0,
    $focalLength      = 0;

  static function __static() {
    self::$EMPTY= new self();
  }
  
  /**
   * Set Height
   *
   * @param   int height
   * @return  self
   */
  public function withHeight($height) {
    $this->height= $height;
    return $this;
  }

  /**
   * Set Width
   *
   * @param   int width
   * @return  self
   */
  public function withWidth($width) {
    $this->width= $width;
    return $this;
  }

  /**
   * Set Make
   *
   * @param   string make
   * @return  self
   */
  public function withMake($make) {
    $this->make= $make;
    return $this;
  }

  /**
   * Set Model
   *
   * @param   string model
   * @return  self
   */
  public function withModel($model) {
    $this->model= $model;
    return $this;
  }

  /**
   * Set Lens Model
   *
   * @param   string model
   * @return  self
   */
  public function withLensModel($lensModel) {
    $this->lensModel= $lensModel;
    return $this;
  }

  /**
   * Set Flash
   *
   * @param   int flash
   * @return  self
   */
  public function withFlash($flash) {
    $this->flash= $flash;
    return $this;
  }

  /**
   * Set Orientation
   *
   * @param   int orientation
   * @return  self
   */
  public function withOrientation($orientation) {
    $this->orientation= $orientation;
    return $this;
  }

  /**
   * Set FileName
   *
   * @param   string fileName
   * @return  self
   */
  public function withFileName($fileName) {
    $this->fileName= $fileName;
    return $this;
  }

  /**
   * Set FileSize
   *
   * @param   int fileSize
   * @return  self
   */
  public function withFileSize($fileSize) {
    $this->fileSize= $fileSize;
    return $this;
  }

  /**
   * Set MimeType
   *
   * @param   string mimeType
   * @return  self
   */
  public function withMimeType($mimeType) {
    $this->mimeType= $mimeType;
    return $this;
  }

  /**
   * Set DateTime
   *
   * @param   util.Date dateTime
   * @return  self
   */
  public function withDateTime($dateTime) {
    $this->dateTime= $dateTime;
    return $this;
  }

  /**
   * Set ApertureFNumber
   *
   * @param   string apertureFNumber
   * @return  self
   */
  public function withApertureFNumber($apertureFNumber) {
    $this->apertureFNumber= $apertureFNumber;
    return $this;
  }

  /**
   * Set Software
   *
   * @param   string software
   * @return  self
   */
  public function withSoftware($software) {
    $this->software= $software;
    return $this;
  }

  /**
   * Set ExposureTime
   *
   * @param   string exposureTime
   * @return  self
   */
  public function withExposureTime($exposureTime) {
    $this->exposureTime= $exposureTime;
    return $this;
  }

  /**
   * Set ExposureProgram
   *
   * @param   int exposureProgram
   * @return  self
   */
  public function withExposureProgram($exposureProgram) {
    $this->exposureProgram= $exposureProgram;
    return $this;
  }

  /**
   * Set MeteringMode
   *
   * @param   int meteringMode
   * @return  self
   */
  public function withMeteringMode($meteringMode) {
    $this->meteringMode= $meteringMode;
    return $this;
  }

  /**
   * Set Whitebalance
   *
   * @param   int whitebalance
   * @return  self
   */
  public function withWhitebalance($whitebalance) {
    $this->whitebalance= $whitebalance;
    return $this;
  }

  /**
   * Set IsoSpeedRatings
   *
   * @param   int isoSpeedRatings
   * @return  self
   */
  public function withIsoSpeedRatings($isoSpeedRatings) {
    $this->isoSpeedRatings= $isoSpeedRatings;
    return $this;
  }

  /**
   * Set FocalLength
   *
   * @param   int FocalLength
   * @return  self
   */
  public function withFocalLength($focallength) {
    $this->focalLength= $focallength;
    return $this;
  }

  /**
   * Retrieve whether the flash was used.
   *
   * - 0 = flash fired
   * - 1 = return detected
   * - 2 = return able to be detected
   * - 3 = unknown
   * - 4 = auto used
   * - 5 = unknown
   * - 6 = red eye reduction used
   *
   * @see     http://www.drewnoakes.com/code/exif/
   * @see     http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif/flash.html
   * @return  bool
   */
  public function flashUsed() {
    return 1 == ($this->flash & 1);
  }
  
  /**
   * Returns whether picture is horizontal
   *
   * @see     http://sylvana.net/jpegcrop/exif_orientation.html
   * @return  bool
   */
  public function isHorizontal() {
    return $this->orientation <= 4;
  }

  /**
   * Returns whether picture is vertical
   *
   * @see     http://sylvana.net/jpegcrop/exif_orientation.html
   * @return  bool
   */
  public function isVertical() {
    return $this->orientation > 4;
  }
  
  /**
   * The orientation of the camera relative to the scene, when the 
   * image was captured. The relation of the '0th row' and '0th column' 
   * to visual position is shown as below:
   *
   * ```
   * +---------------------------------+-----------------+
   * | value | 0th row    | 0th column | human readable  |
   * +---------------------------------+-----------------+
   * | 1     | top        | left side  | normal          |
   * | 2     | top        | right side | flip horizontal |
   * | 3     | bottom     | right side | rotate 180°     |
   * | 4     | bottom     | left side  | flip vertical   |
   * | 5     | left side  | top        | transpose       |
   * | 6     | right side | top        | rotate 90°      |
   * | 7     | right side | bottom     | transverse      |
   * | 8     | left side  | bottom     | rotate 270°     |
   * +---------------------------------+-----------------+
   * ```
   *
   * @return  string
   */
  public function getOrientationString() {
    static $string= [
      1 => 'normal',
      2 => 'flip_horizonal',
      3 => 'rotate_180',
      4 => 'flip_vertical',
      5 => 'transpose',
      6 => 'rotate_90',
      7 => 'transverse',
      8 => 'rotate_270' 
    ];
    return $string[$this->orientation] ?? '(unknown)';
  }
  
  /**
   * Get degree of rotation (one of 0, 90, 180 or 270)
   *
   * @see     http://sylvana.net/jpegcrop/exif_orientation.html
   * @return  int
   */
  public function getRotationDegree() {
    static $degree= [
      3 => 180,   // flip
      6 => 90,    // clockwise
      8 => 270    // counterclockwise
    ];
    return $degree[$this->orientation] ?? 0;
  }

  /**
   * Get String describing exposureProgram value.
   *
   * @return  string
   */
  public function getExposureProgramString() {
    static $ep= [
      0 => 'not defined',
      1 => 'manual',
      2 => 'normal program',
      3 => 'aperture priority',
      4 => 'shutter priority',
      5 => 'creative program',    // (biased toward depth of field)
      6 => 'action program',      // (biased toward fast shutter speed)
      7 => 'portrait mode',       // (for closeup photos with the background out of focus)
      8 => 'landscape mode',      // (for landscape photos with the background in the focus)
    ];
    
    return $ep[$this->exposureProgram] ?? 'n/a';
  }    

  /**
   * Get string describing meteringMode value.
   *
   * @return  string
   */
  public function getMeteringModeString() {
    static $mm= [
      0   => 'unknown',                 
      1   => 'average',                 
      2   => 'center weighted average', 
      3   => 'spot',                    
      4   => 'multispot',               
      5   => 'pattern',                 
      6   => 'partial',                 
      255 => 'other'
    ];
    
    return $mm[$this->meteringMode] ?? 'n/a';
  }

  /**
   * Retrieve a string representation
   *
   * @return  string
   */
  public function toString() {
    return sprintf(
      "%s(%d x %d %s)@{\n".
      "  [file            ] %s (%d bytes)\n".
      "  [make            ] %s\n".
      "  [model           ] %s\n".
      "  [lensModel       ] %s\n".
      "  [software        ] %s\n".
      "  [flash           ] %d (%s)\n".
      "  [orientation     ] %s (%s, %s)\n".
      "  [dateTime        ] %s\n".
      "  [apertureFNumber ] %s\n".
      "  [exposureTime    ] %s\n".
      "  [exposureProgram ] %s (%s)\n".
      "  [meteringMode    ] %s (%s)\n".
      "  [whitebalance    ] %s\n".
      "  [isoSpeedRatings ] %s\n".
      "  [focalLength     ] %s\n".
      "}",
      nameof($this),
      $this->width,
      $this->height,
      $this->mimeType,
      $this->fileName,
      $this->fileSize,
      $this->make,
      $this->model,
      $this->lensModel,
      $this->software,
      $this->flash, 
      $this->flashUsed() ? 'on' : 'off',
      $this->orientation,
      $this->isHorizontal() ? 'horizontal' : 'vertical',
      $this->getOrientationString(),
      $this->dateTime ? $this->dateTime->toString() : 'null',
      $this->apertureFNumber,
      $this->exposureTime,
      $this->exposureProgram,
      $this->getExposureProgramString(),
      $this->meteringMode,
      $this->getMeteringModeString(),
      $this->whitebalance,
      $this->isoSpeedRatings,
      $this->focalLength
    );
  }

  /** @return string */
  public function hashCode() {
    return Objects::hashOf([
      $this->height,
      $this->width,
      $this->make,
      $this->model,
      $this->flash,
      $this->orientation,
      $this->fileName,
      $this->fileSize,
      $this->mimeType,
      $this->dateTime,
      $this->apertureFNumber,
      $this->software,
      $this->exposureTime,
      $this->exposureProgram,
      $this->whitebalance,
      $this->meteringMode,
      $this->isoSpeedRatings,
      $this->focalLength
    ]);
  }

  /**
   * Compares this ImageInfo instance to another value
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare([
          $this->height,
          $this->width,
          $this->make,
          $this->model,
          $this->flash,
          $this->orientation,
          $this->fileName,
          $this->fileSize,
          $this->mimeType,
          $this->dateTime,
          $this->apertureFNumber,
          $this->software,
          $this->exposureTime,
          $this->exposureProgram,
          $this->whitebalance,
          $this->meteringMode,
          $this->isoSpeedRatings,
          $this->focalLength
        ], [
          $value->height,
          $value->width,
          $value->make,
          $value->model,
          $value->flash,
          $value->orientation,
          $value->fileName,
          $value->fileSize,
          $value->mimeType,
          $value->dateTime,
          $value->apertureFNumber,
          $value->software,
          $value->exposureTime,
          $value->exposureProgram,
          $value->whitebalance,
          $value->meteringMode,
          $value->isoSpeedRatings,
          $value->focalLength
        ])
      : 1
    ;
  }
}