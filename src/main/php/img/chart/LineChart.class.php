<?php namespace img\chart;



/**
 * Line chart
 *
 * @see      xp://img.chart.Chart
 * @purpose  Chart
 */
class LineChart extends Chart {
  public
    $distance  = DISTANCE_AUTO,
    $range     = [RANGE_AUTO, RANGE_AUTO, RANGE_AUTO],
    $accumulated= false;

  /**
   * Helper method which returns the largest value from all series
   *
   * @return  float
   */
  public function max() {
    if (!$this->getAccumulated()) return parent::max();
    
    $max= [];
    for ($i= 0, $s= sizeof($this->series); $i < $s; $i++) {
      for ($j= 0, $c= sizeof($this->series[$i]->values); $j < $c; $j++) {
        @$max[$j] += $this->series[$i]->values[$j];
      }
    }
    return max($max);
  }

  /**
   * Helper method which returns the smallest value from all series
   *
   * @return  float
   */
  public function min() {
    if ($this->getAccumulated()) return parent::min();

    $min= [];
    for ($i= 0, $s= sizeof($this->series); $i < $s; $i++) {
      for ($j= 0, $c= sizeof($this->series[$i]->values); $j < $c; $j++) {
        @$min[$j] += $this->series[$i]->values[$j];
      }
    }
    return min($min) < 0 ? min($min) : 0;
  }

  /**
   * Set range. Pass RANGE_AUTO to upper, lower and/or step to have 
   * this value calculated automatically (default behaviour).
   *
   * @param   float lower
   * @param   float upper
   * @param   float step
   */
  public function setRange($lower, $upper, $step) {
    $this->range= [$lower, $upper, $step];
  }

  /**
   * Get range
   *
   * @param   float[] the lower and upper range and the range setp, in this order
   */
  public function getRange() {
    return $this->range;
  }

  /**
   * Set distance between the bars. Pass the DISTANCE_AUTO constant to
   * have it calculated automatically.
   *
   * @param   int distance
   */
  public function setDistance($distance) {
    $this->distance= $distance;
  }

  /**
   * Get distance between the bars
   *
   * @return  int
   */
  public function getDistance() {
    return $this->distance;
  }
  
  /**
   * Set flag to accumulate series
   *
   * @return bool
   */
  public function getAccumulated() {
    return $this->accumulated;
  }
  
  /**
   * Returns flag to accumulate series
   *
   * @param bool bool The flag
   */
  public function setAccumulated($bool) {
    $this->accumulated= $bool;
  }
}