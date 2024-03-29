<?php namespace img\io;

use xml\dom\Document;


/**
 * XMP/XAP meta data segment
 * 
 */
class XMPSegment extends Segment {
  protected $document= null;

  /**
   * Creates a segment instance
   *
   * @param string $marker
   * @param xml.dom.Document document
   */
  public function __construct($marker, Document $document) {
    parent::__construct($marker, null);
    $this->document= $document;
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
    return new self($marker, Document::fromString(trim(substr($bytes, 28), "\x00")));
  }

  /**
   * Gets XML document
   *
   * @return xml.dom.Document
   */
  public function document() {
    return $this->document;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'<'.$this->marker.'>'.\xp::stringOf($this->document);
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
      $cmp->document->equals($this->document)
    );
  }
}