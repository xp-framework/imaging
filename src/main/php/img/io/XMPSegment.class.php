<?php namespace img\io;

use DOMDocument;
use lang\FormatException;

/** XMP/XAP meta data segment */
class XMPSegment extends Segment {
  public $source;
  private $document= null;

  /**
   * Creates a segment instance
   *
   * @param string $marker
   * @param string $source
   */
  public function __construct($marker, $source) {
    parent::__construct($marker, null);
    $this->source= $source;
  }

  /**
   * Creates a segment instance
   *
   * @param  string $marker
   * @param  string $bytes
   * @return self
   */
  public static function read($marker, $bytes) {

    // Begin parsing after 28 bytes - strlen("http://ns.adobe.com/xap/1.0/")
    return new self($marker, trim(substr($bytes, 28), "\x00"));
  }

  /**
   * Gets XML document
   *
   * @return DOMDocument
   * @throws lang.FormatException if parsing the XML fails
   */
  public function document() {
    if (null === $this->document) {
      $this->document= new DOMDocument();
      if (false === $this->document->loadXML($this->source)) {
        $this->document= null;
        $e= new FormatException('Cannot parse XMP/XAP segment');
        \xp::gc(__FILE__);
        throw $e;
      }
    }
    return $this->document;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'('.$this->marker.')'.$this->source;
  }

  /**
   * Test for equality
   *
   * @param  var cmp
   * @return bool
   */
  public function equals($cmp) {
    return (
      $cmp instanceof self &&
      $cmp->marker === $this->marker &&
      $cmp->source === $this->source
    );
  }
}