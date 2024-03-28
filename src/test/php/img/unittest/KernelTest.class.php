<?php namespace img\unittest;

use img\filter\Kernel;
use lang\IllegalArgumentException;
use test\Assert;
use test\{Expect, Test, TestCase};

/**
 * Tests the kernel class
 *
 * @see  xp://img.filter.Kernel
 */
class KernelTest {

  #[Test]
  public function create_from_array() {
    $matrix= [
      [-1.0, -1.0, -1.0], 
      [-1.0, 16.0, -1.0], 
      [-1.0, -1.0, -1.0]
    ];

    $k= new Kernel($matrix);
    Assert::equals($matrix, $k->getMatrix());
  }

  #[Test]
  public function create_from_string() {
    $string= '[[-1.0, -1.0, -1.0], [-1.0, 16.0, -1.0], [-1.0, -1.0, -1.0]]';
    $matrix= [
      [-1.0, -1.0, -1.0], 
      [-1.0, 16.0, -1.0], 
      [-1.0, -1.0, -1.0]
    ];

    $k= new Kernel($string);
    Assert::equals($matrix, $k->getMatrix());
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function create_from_empty_array() {
    new Kernel([]);
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function create_from_array_with_incorrect_row_size() {
    $matrix= [
      [-1.0, -1.0, -1.0], 
      [-1.0, 16.0, -1.0, 6100], 
      [-1.0, -1.0, -1.0]
    ];
    new Kernel($matrix);
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function create_with_malformed_string() {
    new Kernel('@@SYNTAX-ERROR@@');
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function create_from_string_with_incorrect_row_size() {
    new Kernel('[[-1.0, -1.0, -1.0], [-1.0, -1.0], [-1.0, -1.0, -1.0]]');
  }
}