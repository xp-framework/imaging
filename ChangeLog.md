Imaging APIs for the XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

## 7.1.1 / 2017-05-20

* Refactored code to use `typeof()` instead of `xp::typeOf()`, see
  https://github.com/xp-framework/rfc/issues/323
  (@thekid)

## 7.1.0 / 2016-08-29

* Added forward compatibility with XP 8.0.0 - @thekid

## 7.0.0 / 2016-02-22

* **Adopted semantic versioning. See xp-framework/rfc#300** - @thekid 
* Added version compatibility with XP 7 - @thekid

## 6.1.1 / 2016-01-23

* Fix code to use `nameof()` instead of the deprecated `getClassName()`
  method from lang.Generic. See xp-framework/core#120
  (@thekid)

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
