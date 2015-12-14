Imaging APIs for the XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

## 6.1.0 / 2015-12-14

* **Heads up**: Changed minimum XP version to XP 6.5.0, and with it the
  minimum PHP version to PHP 5.5.
  (@thekid)
* Removed calls to deprecated `this()` - @thekid
* Removed support for deprecated `io.Stream` instances - @thekid

## 6.0.3 / 2015-07-12

* Added forward compatibility with XP 6.4.0 - @thekid
* Added preliminary PHP 7 support (alpha2, beta1) - @thekid

## 6.0.2 / 2015-03-01

* Fixed img.io.StreamReader and img.io.StreamWriter to support io.File
  objects again after XP 6 removed its subclassing io.Stream - @thekid

## 6.0.1 / 2015-02-12

* Changed dependency to use XP ~6.0 (instead of dev-master) - @thekid

## 6.0.0 / 2015-10-01

* Heads up: Converted classes to PHP 5.3 namespaces - (@thekid)
