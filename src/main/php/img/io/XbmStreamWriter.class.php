<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses('img.io.StreamWriter');

  /**
   * Writes XBM to a stream
   *
   * @ext   gd
   * @see   php://imagexbm
   * @see   xp://img.io.StreamWriter
   */
  class XbmStreamWriter extends StreamWriter {
    public $foreground  = 0;
    
    /**
     * Constructor
     *
     * @param   io.Stream stream
     * @param   int foreground default 0
     */
    public function __construct($stream, $foreground= 0) {
      parent::__construct($stream);
      $this->foreground= $foreground;
    }

    /**
     * Output an image
     *
     * @param   resource handle
     * @return  bool
     */    
    public function output($handle) {
      return imagexbm($handle, '', $this->foreground);
    }
  }
?>
