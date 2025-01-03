<?php namespace img\io;

use img\ImagingException;
use io\streams\InputStream;

/**
 * Reads meta data from JPEG files
 *
 * ```php
 * $reader= new MetaDataReader();
 * $meta= $reader->read($file->in(), $file->getURI());
 *
 * $exif= $meta->exifData();
 * $iptc= $meta->iptcData();
 * ```
 *
 * @see  https://php.net/exif_read_data
 * @see  https://php.net/iptcparse
 * @see  https://php.net/getimagesize
 * @test net.xp_framework.unittest.img.MetaDataReaderTest
 */
class MetaDataReader {
  protected static $seg= [
    "\x01" => 'TEM',   "\x02" => 'RES',

    "\xc0" => 'SOF0',  "\xc1" => 'SOF1',  "\xc2" => 'SOF2',  "\xc3" => 'SOF4',
    "\xc4" => 'DHT',   "\xc5" => 'SOF5',  "\xc6" => 'SOF6',  "\xc7" => 'SOF7',  
    "\xc8" => 'JPG',   "\xc9" => 'SOF9',  "\xca" => 'SOF10', "\xcb" => 'SOF11', 
    "\xcc" => 'DAC',   "\xcd" => 'SOF13', "\xce" => 'SOF14', "\xcf" => 'SOF15', 

    "\xd0" => 'RST0',  "\xd1" => 'RST1',  "\xd2" => 'RST2',  "\xd3" => 'RST3',
    "\xd4" => 'RST4',  "\xd5" => 'RST5',  "\xd6" => 'RST6',  "\xd7" => 'RST7',
    "\xd8" => 'SOI',   "\xd9" => 'EOI',   "\xda" => 'SOS',   "\xdb" => 'DQT',
    "\xdc" => 'DNL',   "\xdd" => 'DRI',   "\xde" => 'DHP',   "\xdf" => 'EXP',

    "\xe0" => 'APP0',  "\xe1" => 'APP1',  "\xe2" => 'APP2',  "\xe3" => 'APP3',
    "\xe4" => 'APP4',  "\xe5" => 'APP5',  "\xe6" => 'APP6',  "\xe7" => 'APP7',
    "\xe8" => 'APP8',  "\xe9" => 'APP9',  "\xea" => 'APP10', "\xeb" => 'APP11',
    "\xec" => 'APP12', "\xed" => 'APP13', "\xee" => 'APP14', "\xef" => 'APP15',

    "\xf0" => 'JPG0',  "\xf1" => 'JPG1',  "\xf2" => 'JPG2',  "\xf3" => 'JPG3',
    "\xf4" => 'JPG4',  "\xf5" => 'JPG5',  "\xf6" => 'JPG6',  "\xf7" => 'JPG7',
    "\xf8" => 'JPG8',  "\xf9" => 'JPG9',  "\xfa" => 'JPG10', "\xfb" => 'JPG11',
    "\xfc" => 'JPG12', "\xfd" => 'JPG13', "\xfe" => 'COM',   
  ];


  /**
   * Returns a segment
   * 
   * @param  string $marker
   * @param  string $data
   * @return img.io.Segment
   */
  protected function segmentFor($marker, $data) {
    static $impl= [
      'SOF0'  => SOFNSegment::class,    // image width and height
      'APP1'  => APP1Segment::class,    // Exif, XMP
      'APP13' => APP13Segment::class,   // IPTC
      'COM'   => CommentSegment::class
    ];

    if ($seg= self::$seg[$marker] ?? null) {
      if ($class= $impl[$seg] ?? null) {
        return $class::read($seg, $data);
      } else {
        return new Segment($seg, $data);
      }
    }
    return new Segment(sprintf('0x%02x', ord($marker)), $data);
  }

  /**
   * Read a given amount of bytes and does not return until this 
   * amount has been reached (except if there is no more data 
   * available!)
   *
   * @param  io.streams.InputStream $in
   * @param  int $size
   * @return string
   */
  protected function readFully($in, $size) {
    $data= '';
    $l= 0;
    do {
      $data.= $in->read($size - $l);
      $l= strlen($data);
    } while ($in->available() && $l < $size);
    return $data;
  }

  /**
   * Reads meta data from the given input stream
   *
   * @param  io.streams.InputStream $in The input stream to read from
   * @param  string $name The input stream's name
   * @return img.io.ImageMetaData
   * @throws img.ImagingException if the input stream cannot be parsed
   */
  public function read(InputStream $in, $name= 'input stream') {
    if ("\xff\xd8\xff" !== $this->readFully($in, 3)) {
      throw new ImagingException('Could not find start of image marker in JPEG data '.$name);
    }
    $offset= 3;

    // Parse JPEG headers
    $data= new ImageMetaData();
    $data->setSource($name);
    while ("\xd9" !== ($marker= $this->readFully($in, 1))) {
      $offset++;
      if ("\xda" === $marker) break;      // Stop at SOS (Start Of Scan)

      if ($marker < "\xd0" || $marker > "\xd7") {
        $size= current(unpack('n', $this->readFully($in, 2)));
        $data->addSegment($this->segmentFor($marker, $this->readFully($in, $size - 2)));
        $offset+= $size;
      }

      if ("\xff" !== ($c= $this->readFully($in, 1))) {
        throw new ImagingException(sprintf(
          'JPEG header corrupted, have x%02x, expecting xff at offset %d',
          ord($c),
          $offset
        ));
      }
      $offset++;
    }
    return $data;
  }
}