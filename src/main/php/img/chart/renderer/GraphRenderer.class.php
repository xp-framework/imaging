<?php namespace img\chart\renderer;

/**
 * Renderer
 *
 * @see      xp://img.chart.Chart
 * @purpose  Interface
 */
interface GraphRenderer {

  /**
   * Renders a chart
   *
   * @param   img.chart.Chart chart
   * @return  var
   */
  public function render(\img\chart\Chart $chart);
}
