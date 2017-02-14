WpLink
======

Detect if a link is internal to enable easily setting `target="_blank"` for external links.

## Usage
Require the library with composer and use it in your blades like this:
```blade
<a href="{{ $uri }}" {!! \Onvardgmbh\WpLink::target($uri) !!}>Foo</a>
```
