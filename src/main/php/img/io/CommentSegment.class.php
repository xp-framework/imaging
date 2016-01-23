<?php namespace img\io;



/**
 * COM meta data segment
 * 
 */
class CommentSegment extends Segment {
  protected $text= [];

  /**
   * Creates a segment instance
   *
   * @param string $marker
   * @param [:int] $data
   */
  public function __construct($marker, $text) {
    parent::__construct($marker, null);
    $this->text= $text;
  }

  /**
   * Creates a segment instance
   *
   * @param  string $marker
   * @param  string $bytes
   * @return self
   */
  public static function read($marker, $bytes) {
    return new self($marker, $bytes);
  }

  /**
   * Get image bits
   *
   * @return int
   */
  public function text() {
    return $this->text;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'<'.$this->marker.'>'.\xp::stringOf($this->text);
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
      $cmp->text === $this->text
    );
  }
}
