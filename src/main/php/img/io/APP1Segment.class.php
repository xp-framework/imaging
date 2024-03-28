<?php namespace img\io;



/**
 * APP1 meta data segment
 * 
 */
class APP1Segment extends Segment {

  /**
   * Creates a segment instance
   *
   * @param  string $marker
   * @param  string $bytes
   * @return self
   */
  public static function read($marker, $bytes) {
    if (0 === strncmp('Exif', $bytes, 4)) {
      return ExifSegment::read($marker, $bytes);
    } else if (0 === strncmp('http://ns.adobe.com/xap/1.0/', $bytes, 28)) {
      return XMPSegment::read($marker, $bytes);
    } else {
      return new self($marker, $bytes);
    }
  }
}