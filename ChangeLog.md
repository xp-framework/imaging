Imaging APIs for the XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

## 10.0.0 / 2022-02-28

* Implemented xp-framework/rfc#341: Drop XP 9 compatibility - @thekid
* Added XP 11 compatibility - @thekid

## 9.0.0 / 2020-04-10

* Implemented xp-framework/rfc#334: Drop PHP 5.6:
  . **Heads up:** Minimum required PHP version now is PHP 7.0.0
  . Rewrote code base, grouping use statements
  . Converted `newinstance` to anonymous classes
  . Rewrote `isset(X) ? X : default` to `X ?? default`
  (@thekid)

## 8.0.2 / 2020-04-05

* Implemented RFC #335: Remove deprecated key/value pair annotation syntax
  (@thekid)

## 8.0.1 / 2020-04-04

* Made compatible with XP 10 - @thekid

## 8.0.0 / 2017-08-01

* **Heads up:** Removed support for deprecated `io.Stream` instances
  (@thekid)
* Changed Stream(Reader|Writer) constructors to accept `io.Channel`
  instances as well as `io.streams.InputStream`.
  (@thekid)
* Added constants `Image::TRUECOLOR` and `Image::PALETTE`, deprecating
  the global defines with the same name and an `IMG_` prefix
  (@thekid)
* **Heads up:** Added forward compatibility with XP 9; dropped PHP 5.5
  support, minimum PHP version is now PHP 5.6.
  (@thekid)

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
