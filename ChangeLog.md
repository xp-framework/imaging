Imaging APIs for the XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

## 11.0.0 / 2024-03-29

* Removed deprecated global constants `IMG_PALETTE` / `IMG_TRUECOLOR`
  (@thekid)
* Removed field getters and setters from the `ExifData` and `IptcData`
  classes in `img.util`.
  (@thekid)
* Dropped support for PHP 7.0 - 7.3, step 1 of xp-framework/rfc#343
  (@thekid)
* Merged PR #4: Remove deprecated `(ExifData|IptcData)::fromString()`
  (@thekid)
* Merged PR #3: Drop dependency on XML library, decreasing library size
  (@thekid)

## 10.5.0 / 2024-03-29

* Made compatible with `xp-framework/xml` 12.0+ - @thekid

## 10.4.0 / 2024-03-28

* Improved performance when reading meta data - @thekid
* Migrated to new testing library - @thekid

## 10.3.0 / 2024-03-24

* Made compatible with XP 12 - @thekid

## 10.2.0 / 2023-11-04

* Added PHP 8.3 and PHP 8.4 to test matrix - @thekid
* Merged PR #2: Add Color::intValue() to convert colors to ARGB color ints
  (@thekid)

## 10.1.2 / 2022-10-02

* Fixed "Invalid document end" in certain XMP segments - @thekid

## 10.1.1 / 2022-10-01

* Fixed "Cannot redeclare img\util\ImageInfo::hashCode()" - @thekid

## 10.1.0 / 2022-09-10

* Merged PR #1: Add WebP support - @thekid

## 10.0.0 / 2022-02-28

* Fixed PHP 8.0+ compatiblity: The image creation functions now return
  a *GdImage* instance instead of a resource
  (@thekid)
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
