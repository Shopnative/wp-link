WpLink
======
[![Build Status](https://travis-ci.com/onvardgmbh/wp-link.svg?token=CUVxqyDLPnf9Hqbcbzfs&branch=master)](https://travis-ci.com/onvardgmbh/wp-link)

Detect if a link is internal to enable easily setting `target="_blank"` for external links.

## Usage
Require the library with composer and use it in your blades like this:
```blade
<a href="{{ $uri }}" {!! \Onvardgmbh\WpLink::target($uri) !!}>Foo</a>
```

## Run Tests
To run the tests, run `composer run-script test` or `composer run-script test:dox` in the project root directory.
