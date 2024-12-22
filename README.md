Imaging APIs for the XP Framework
========================================================================

[![Build status on GitHub](https://github.com/xp-framework/imaging/workflows/Tests/badge.svg)](https://github.com/xp-framework/imaging/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_4plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-framework/imaging/version.svg)](https://packagist.org/packages/xp-framework/imaging)

Loading an image
----------------

```php
use img\Image;
use img\io\JpegStreamReader;
use io\File;

$image= Image::loadFrom(new JpegStreamReader(new File('image.jpeg')));

// Can now be manipulated
```

Manipulating an image
---------------------

Resizing the original image to 640x480

```php
use img\Image;

$transformed= Image::create(640, 480, Image::TRUECOLOR);
$transformed->resampleFrom($image);
```

Apply filters:

```php
use img\filters\SharpenFilter;

$transformed->apply(new SharpenFilter());
```

Convert:

```php
use img\convert\GrayscaleConverter;

$transformed->convertTo(new GrayscaleConverter());
```

Saving an image
---------------

```php
use img\io\JpegStreamWriter;
use io\File;

$transformed->saveTo(new JpegStreamWriter(new File('transformed.jpeg'), 100));
```
