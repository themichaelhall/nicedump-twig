# NiceDump extension for Twig

[![Build Status](https://travis-ci.org/themichaelhall/nicedump-twig.svg?branch=master)](https://travis-ci.org/themichaelhall/nicedump-twig)
[![AppVeyor](https://ci.appveyor.com/api/projects/status/github/themichaelhall/nicedump-twig?branch=master&svg=true)](https://ci.appveyor.com/project/themichaelhall/nicedump-twig/branch/master)
[![codecov.io](https://codecov.io/gh/themichaelhall/nicedump-twig/coverage.svg?branch=master)](https://codecov.io/gh/themichaelhall/nicedump-twig?branch=master)
[![StyleCI](https://styleci.io/repos/163513640/shield?style=flat&branch=master)](https://styleci.io/repos/163513640)
[![License](https://poser.pugx.org/nicedump/nicedump-twig/license)](https://packagist.org/packages/nicedump/nicedump-twig)
[![Latest Stable Version](https://poser.pugx.org/nicedump/nicedump-twig/v/stable)](https://packagist.org/packages/nicedump/nicedump-twig)
[![Total Downloads](https://poser.pugx.org/nicedump/nicedump-twig/downloads)](https://packagist.org/packages/nicedump/nicedump-twig)

Twig extension to dump a variable according to the [NiceDump format specification](https://nicedump.net/). 

## Requirements

- PHP >= 7.3

## Install with composer

``` bash
$ composer require nicedump/nicedump-twig
```

## Basic usage

After enabling this extension, the ```nice_dump()``` function can be used in Twig templates to output a variable in NiceDump format.

The variable is only output if debug mode is enabled in Twig, otherwise ```nice_dump()``` returns just an empty string. This makes it possible to use the function both in development and production mode.

### Dump a variable

```
{{ nice_dump(foo) }}
```

This may output something like this:

``` html
<!--
=====BEGIN NICE-DUMP=====
eyJ0eXBlIjoic3RyaW5nIiwidmFsdWUiOiJGb28iLCJzaXplIjozfQ==
=====END NICE-DUMP=====
-->
```

Notice that the NiceDump in enclosed in a HTML comment.

### Dump a variable with a name

```
{{ nice_dump(foo, 'Foo') }}
```

### Dump a variable with a name and a comment

```
{{ nice_dump(foo, 'Foo', 'This is my Foo') }}
```

### Enable output in release mode

**Caution: This may unintentionally reveal secret data on a production server. Use with care!**

Enable ```nice_dump()``` to output a NiceDump, even when Twig is in non-debug mode:

``` php
use NiceDumpTwig\NiceDumpTwigExtension;

$extension = new NiceDumpTwigExtension();
$extension->enableInReleaseMode();
```

## License

MIT
