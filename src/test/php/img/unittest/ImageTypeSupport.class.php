<?php namespace img\unittest;

use test\assert\{Assertion, Verify};
use test\execution\Context;
use test\verify\Verification;

/** Tests image type support via `imagetypes()` */
class ImageTypeSupport implements Verification {
  private $types;

  /**
   * Constructor
   *
   * @param string... $types
   */
  public function __construct(... $types) {
    $this->types= $types;
  }

  /**
   * Yields assertions to verify runtime OS / PHP
   *
   * @param  test.execution.Context $context
   * @return iterable
   */
  public function assertions(Context $context) {
    yield new Assertion(
      extension_loaded('gd'),
      new Verify('PHP extension "gd" is loaded')
    );

    $supported= imagetypes();
    foreach ($this->types as $type) {
      yield new Assertion(
        $supported & constant('IMG_'.strtoupper($type)),
        new Verify('Image type "'.$type.'" is supported')
      );
    }
  }
}