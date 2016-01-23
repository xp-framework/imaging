<?php namespace img\io;



/**
 * IPTC meta data segment
 * 
 */
class IptcSegment extends Segment {
  protected $data= [];

  /**
   * Creates a segment instance
   *
   * @param string $marker
   * @param [:int] $data
   */
  public function __construct($marker, $data) {
    parent::__construct($marker, null);
    $this->data= $data;
  }

  /**
   * Returns the raw data
   * 
   * @return [:var]
   */
  public function rawData() {
    return $this->data;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'<'.$this->marker.'>'.\xp::stringOf($this->data);
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
      $cmp->data === $this->data
    );
  }
}
