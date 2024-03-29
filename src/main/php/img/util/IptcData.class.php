<?php namespace img\util;

use lang\Value;
use util\{Date, Objects};

/**
 * IPTC headers from Photoshop-files JPEGs or TIFFs
 *
 * @test  net.xp_framework.unittest.img.IptcDataTest
 * @see   http://photothumb.com/IPTCExt/
 * @see   http://www.controlledvocabulary.com/pdf/IPTC_mapped_fields.pdf
 */
class IptcData implements Value {
  public static $EMPTY= null;

  public
    $title                         = '',
    $urgency                       = '',
    $category                      = '',
    $keywords                      = [], 
    $dateCreated                   = null, 
    $author                        = '', 
    $authorPosition                = '', 
    $city                          = '', 
    $state                         = '', 
    $country                       = '', 
    $headline                      = '', 
    $credit                        = '', 
    $source                        = '', 
    $copyrightNotice               = '', 
    $caption                       = '', 
    $writer                        = '', 
    $specialInstructions           = '',
    $supplementalCategories        = [],
    $originalTransmissionReference = '';

  static function __static() {
    self::$EMPTY= new self();
  }

  /**
   * Set Title
   *
   * @param   string title
   * @return  self
   */
  public function withTitle($title) {
    $this->title= $title;
    return $this;
  }

  /**
   * Set Urgency
   *
   * @param   string urgency
   * @return  self
   */
  public function withUrgency($urgency) {
    $this->urgency= $urgency;
    return $this;
  }

  /**
   * Set Category
   *
   * @param   string category
   * @return  self
   */
  public function withCategory($category) {
    $this->category= $category;
    return $this;
  }

  /**
   * Set Keywords
   *
   * @param   string[] keywords
   * @return  self
   */
  public function withKeywords($keywords) {
    $this->keywords= $keywords;
    return $this;
  }

  /**
   * Set DateCreated
   *
   * @param   ?util.Date dateCreated default NULL
   * @return  self
   */
  public function withDateCreated($dateCreated= null) {
    $this->dateCreated= $dateCreated;
    return $this;
  }

  /**
   * Set Author
   *
   * @param   string author
   * @return  self
   */
  public function withAuthor($author) {
    $this->author= $author;
    return $this;
  }

  /**
   * Set AuthorPosition
   *
   * @param   string authorPosition
   * @return  self
   */
  public function withAuthorPosition($authorPosition) {
    $this->authorPosition= $authorPosition;
    return $this;
  }

  /**
   * Set City
   *
   * @param   string city
   * @return  self
   */
  public function withCity($city) {
    $this->city= $city;
    return $this;
  }

  /**
   * Set State
   *
   * @param   string state
   * @return  self
   */
  public function withState($state) {
    $this->state= $state;
    return $this;
  }

  /**
   * Set Country
   *
   * @param   string country
   * @return  self
   */
  public function withCountry($country) {
    $this->country= $country;
    return $this;
  }

  /**
   * Set Headline
   *
   * @param   string headline
   * @return  self
   */
  public function withHeadline($headline) {
    $this->headline= $headline;
    return $this;
  }

  /**
   * Set Credit
   *
   * @param   string credit
   * @return  self
   */
  public function withCredit($credit) {
    $this->credit= $credit;
    return $this;
  }

  /**
   * Set Source
   *
   * @param   string source
   * @return  self
   */
  public function withSource($source) {
    $this->source= $source;
    return $this;
  }

  /**
   * Set CopyrightNotice
   *
   * @param   string copyrightNotice
   * @return  self
   */
  public function withCopyrightNotice($copyrightNotice) {
    $this->copyrightNotice= $copyrightNotice;
    return $this;
  }

  /**
   * Set Caption
   *
   * @param   string caption
   * @return  self
   */
  public function withCaption($caption) {
    $this->caption= $caption;
    return $this;
  }

  /**
   * Set Writer
   *
   * @param   string writer
   * @return  self
   */
  public function withWriter($writer) {
    $this->writer= $writer;
    return $this;
  }

  /**
   * Set SupplementalCategories
   *
   * @param   string[] supplementalCategories
   * @return  self
   */
  public function withSupplementalCategories($supplementalCategories) {
    $this->supplementalCategories= $supplementalCategories;
    return $this;
  }

  /**
   * Set SpecialInstructions
   *
   * @param   string specialInstructions
   * @return  self
   */
  public function withSpecialInstructions($specialInstructions) {
    $this->specialInstructions= $specialInstructions;
    return $this;
  }

  /**
   * Set OriginalTransmissionReference
   *
   * @param   string originalTransmissionReference
   * @return  self
   */
  public function withOriginalTransmissionReference($originalTransmissionReference) {
    $this->originalTransmissionReference= $originalTransmissionReference;
    return $this;
  }

  /** @return string */
  public function toString() {
    return sprintf(
      "  [title                        ] %s\n".
      "  [urgency                      ] %s\n".
      "  [category                     ] %s\n".
      "  [keywords                     ] %s\n".
      "  [dateCreated                  ] %s\n".
      "  [author                       ] %s\n".
      "  [authorPosition               ] %s\n".
      "  [city                         ] %s\n".
      "  [state                        ] %s\n".
      "  [country                      ] %s\n".
      "  [headline                     ] %s\n".
      "  [credit                       ] %s\n".
      "  [source                       ] %s\n".
      "  [copyrightNotice              ] %s\n".
      "  [caption                      ] %s\n".
      "  [writer                       ] %s\n".
      "  [supplementalCategories       ] %s\n".
      "  [specialInstructions          ] %s\n".
      "  [originalTransmissionReference] %s\n".
      "}",  
      $this->title,
      $this->urgency,
      $this->category,
      Objects::stringOf($this->keywords, '  '),
      $this->dateCreated ? $this->dateCreated->toString() : 'null',
      $this->author,
      $this->authorPosition,
      $this->city,
      $this->state,
      $this->country,
      $this->headline,
      $this->credit,
      $this->source,
      $this->copyrightNotice,
      $this->caption,
      $this->writer,
      Objects::stringOf($this->supplementalCategories, '  '),
      $this->specialInstructions,
      $this->originalTransmissionReference
    );
  }

  /** @return string */
  public function hashCode() {
    return Objects::hashOf([
      $this->title,
      $this->urgency,
      $this->category,
      $this->keywords,
      $this->dateCreated,
      $this->author,
      $this->authorPosition,
      $this->city,
      $this->state,
      $this->country,
      $this->headline,
      $this->credit,
      $this->source,
      $this->copyrightNotice,
      $this->caption,
      $this->writer,
      $this->specialInstructions,
      $this->supplementalCategories,
      $this->originalTransmissionReference
    ]);
  }

  /**
   * Compares this ExifData instance to another value
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare([
          $this->title,
          $this->urgency,
          $this->category,
          $this->keywords,
          $this->dateCreated,
          $this->author,
          $this->authorPosition,
          $this->city,
          $this->state,
          $this->country,
          $this->headline,
          $this->credit,
          $this->source,
          $this->copyrightNotice,
          $this->caption,
          $this->writer,
          $this->specialInstructions,
          $this->supplementalCategories,
          $this->originalTransmissionReference
        ], [
          $value->title,
          $value->urgency,
          $value->category,
          $value->keywords,
          $value->dateCreated,
          $value->author,
          $value->authorPosition,
          $value->city,
          $value->state,
          $value->country,
          $value->headline,
          $value->credit,
          $value->source,
          $value->copyrightNotice,
          $value->caption,
          $value->writer,
          $value->specialInstructions,
          $value->supplementalCategories,
          $value->originalTransmissionReference
        ])
      : 1
    ;
  }
}