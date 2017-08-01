<?php namespace img\chart;

/**
 * A series of data
 *
 * @see      xp://img.chart.Chart
 * @purpose  Value object
 */
class Series {
  public
    $name   = '',
    $values = [];
    
  /**
   * Constructor
   *
   * @param   string name
   * @param   float[] values default array()
   */
  public function __construct($name, $values= []) {
    $this->name= $name;
    $this->values= $values;
  }
  
  /**
   * Adds a value to this series
   *
   * @param   float f
   */
  public function add($f) {
    $this->values[]= $f;
  }
}
