<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses('img.io.StreamWriter');

  /**
   * Writes GD to a stream
   *
   * @ext   gd
   * @see   php://imagegd
   * @see   xp://img.io.StreamWriter
   */
  class GDStreamWriter extends StreamWriter {
    
    /**
     * Output an image
     *
     * @param   resource handle
     * @return  bool
     */    
    public function output($handle) {
      return imagegd($handle);
    }
  }
?>
