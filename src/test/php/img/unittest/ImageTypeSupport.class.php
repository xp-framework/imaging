<?php namespace img\unittest;

use unittest\Test;
use unittest\TestAction;

/**
 * Tests image type support
 *
 * @see   php://imagetypes
 */
class ImageTypeSupport implements TestAction {
  protected $type= '';

  /**
   * Constructor
   *
   * @param string $type
   */
  public function __construct($type) {
    $this->type= $type;
  }

  /**
   * This method gets invoked before a test method is invoked, and before
   * the setUp() method is called.
   *
   * @param  unittest.Test $t
   * @throws unittest.PrerequisitesNotMetError
   */
  public function beforeTest(Test $t) { 
    if (!(imagetypes() & constant('IMG_'.$this->type))) {
      throw new PrerequisitesNotMetError('Image type not supported', null, [$this->type]);
    }
  }

  /**
   * This method gets invoked after the test method is invoked and regard-
   * less of its outcome, after the tearDown() call has run.
   *
   * @param  unittest.Test $t
   */
  public function afterTest(Test $t) {
    // Empty
  }
}
