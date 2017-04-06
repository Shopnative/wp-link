<?php
declare(strict_types = 1);

use Onvardgmbh\WpLink;
use PHPUnit\Framework\TestCase;

/**
 * @covers WpLink
 */
final class WpLinkTest extends TestCase
{
    public function testTargetInternal()
    {
        global $siteUrl;
        foreach ([
                'http://example.com',
                'https://example.com',
                '//example.com',
                'example.com',
        ] as $url) {
            $siteUrl = $url;

            // Http
            $this->assertEquals('', WpLink::target('http://example.com'));
            $this->assertEquals('', WpLink::target('http://example.com/'));
            $this->assertEquals('', WpLink::target('http://example.com/foo'));
            $this->assertEquals('', WpLink::target('http://example.com/foo/bar.html'));
            $this->assertEquals('', WpLink::target('http://example.com/foo/bar.html?q=narf'));

            // Https
            $this->assertEquals('', WpLink::target('https://example.com'));
            $this->assertEquals('', WpLink::target('https://example.com/'));
            $this->assertEquals('', WpLink::target('https://example.com/foo'));
            $this->assertEquals('', WpLink::target('https://example.com/foo/bar.html'));
            $this->assertEquals('', WpLink::target('https://example.com/foo/bar.html?q=narf'));

            // Same Scheme
            $this->assertEquals('', WpLink::target('//example.com'));
            $this->assertEquals('', WpLink::target('//example.com/'));
            $this->assertEquals('', WpLink::target('//example.com/foo'));
            $this->assertEquals('', WpLink::target('//example.com/foo/bar.html'));
            $this->assertEquals('', WpLink::target('//example.com/foo/bar.html?q=narf'));

            // Relative
            $this->assertEquals('', WpLink::target('example/com'));
            $this->assertEquals('', WpLink::target('foo'));
            $this->assertEquals('', WpLink::target('foo/bar.html'));
            $this->assertEquals('', WpLink::target('foo/bar.html?q=narf'));

            // Absolute
            $this->assertEquals('', WpLink::target('/'));
            $this->assertEquals('', WpLink::target('/example/com'));
            $this->assertEquals('', WpLink::target('/foo'));
            $this->assertEquals('', WpLink::target('/foo/bar.html'));
            $this->assertEquals('', WpLink::target('/foo/bar.html?q=narf'));
        }
    }

    public function testTargetExternal()
    {
        global $siteUrl;

        foreach ([
            'http://example.com',
            'https://example.com',
            '//example.com',
            'example.com',
        ] as $url) {
            $siteUrl = $url;

            // Http
            $this->assertEquals('target="_blank"', WpLink::target('http://external.com'));
            $this->assertEquals('target="_blank"', WpLink::target('http://external.com/'));
            $this->assertEquals('target="_blank"', WpLink::target('http://external.com/foo'));
            $this->assertEquals('target="_blank"', WpLink::target('http://external.com/foo/bar.html'));
            $this->assertEquals('target="_blank"', WpLink::target('http://external.com/foo/bar.html?q=narf'));
            $this->assertEquals('target="_blank"', WpLink::target('http://subdomain.external.com'));
            $this->assertEquals('target="_blank"', WpLink::target('http://subdomain.external.com/'));
            $this->assertEquals('target="_blank"', WpLink::target('http://subdomain.external.com/foo'));
            $this->assertEquals('target="_blank"', WpLink::target('http://subdomain.external.com/foo/bar.html'));
            $this->assertEquals('target="_blank"', WpLink::target('http://subdomain.external.com/foo/bar.html?q=narf'));

            // Https
            $this->assertEquals('target="_blank"', WpLink::target('https://external.com'));
            $this->assertEquals('target="_blank"', WpLink::target('https://external.com/'));
            $this->assertEquals('target="_blank"', WpLink::target('https://external.com/foo'));
            $this->assertEquals('target="_blank"', WpLink::target('https://external.com/foo/bar.html'));
            $this->assertEquals('target="_blank"', WpLink::target('https://external.com/foo/bar.html?q=narf'));
            $this->assertEquals('target="_blank"', WpLink::target('https://subdomain.external.com'));
            $this->assertEquals('target="_blank"', WpLink::target('https://subdomain.external.com/'));
            $this->assertEquals('target="_blank"', WpLink::target('https://subdomain.external.com/foo'));
            $this->assertEquals('target="_blank"', WpLink::target('https://subdomain.external.com/foo/bar.html'));
            $this->assertEquals('target="_blank"', WpLink::target('https://subdomain.external.com/foo/bar.html?q=narf'));

            // External Same Scheme
            $this->assertEquals('target="_blank"', WpLink::target('//external.com'));
            $this->assertEquals('target="_blank"', WpLink::target('//external.com/'));
            $this->assertEquals('target="_blank"', WpLink::target('//external.com/foo'));
            $this->assertEquals('target="_blank"', WpLink::target('//external.com/foo/bar.html'));
            $this->assertEquals('target="_blank"', WpLink::target('//external.com/foo/bar.html?q=narf'));
            $this->assertEquals('target="_blank"', WpLink::target('//subdomain.external.com'));
            $this->assertEquals('target="_blank"', WpLink::target('//subdomain.external.com/'));
            $this->assertEquals('target="_blank"', WpLink::target('//subdomain.external.com/foo'));
            $this->assertEquals('target="_blank"', WpLink::target('//subdomain.external.com/foo/bar.html'));
            $this->assertEquals('target="_blank"', WpLink::target('//subdomain.external.com/foo/bar.html?q=narf'));
        }
    }

    public function testContentFilter()
    {
        global $siteUrl;

        foreach ([
            'http://example.com',
            'https://example.com',
            '//example.com',
            'example.com',
        ] as $url) {
            $siteUrl = $url;

            // Simple case
            $this->assertEquals('<a href="http://example.com">link</a>', WpLink::content('<a href="http://example.com">link</a>'));
            $this->assertEquals('<a href="http://external.com" target="_blank">link</a>', WpLink::content('<a href="http://external.com">link</a>'));

            // Multiple top level elements
            $this->assertEquals('<p>First <a href="http://example.com/">link</a>.</p><p>Second <a href="http://example.com/about">link</a>.</p>',
                str_replace(PHP_EOL, '', WpLink::content('<p>First <a href="http://example.com/">link</a>.</p><p>Second <a href="http://example.com/about">link</a>.</p>')));
            $this->assertEquals('<p>First <a href="http://external.com/" target="_blank">link</a>.</p><p>Second <a href="http://external.com/about" target="_blank">link</a>.</p>',
                str_replace(PHP_EOL, '', WpLink::content('<p>First <a href="http://external.com/">link</a>.</p><p>Second <a href="http://external.com/about">link</a>.</p>')));

            // Nested elements
            $this->assertEquals('<div class="foo">Some text <em>with <strong>a <a class="link" id="test" href="http://example.com/">link</a> and</strong> more</em> text</div>',
                str_replace(PHP_EOL, '', WpLink::content('<div class="foo">Some text <em>with <strong>a <a class="link" id="test" href="http://example.com/">link</a> and</strong> more</em> text</div>')));
            $this->assertEquals('<div class="foo">Some text <em>with <strong>a <a class="link" id="test" href="http://external.com/" target="_blank">link</a> and</strong> more</em> text</div>',
                str_replace(PHP_EOL, '', WpLink::content('<div class="foo">Some text <em>with <strong>a <a class="link" id="test" href="http://external.com/">link</a> and</strong> more</em> text</div>')));

            // Non-ASCII characters
            $this->assertEquals('<p>Umläütß <a href="/">→ Lïnk</a>.</p>', WpLink::content('<p>Umläütß <a href="/">→ Lïnk</a>.</p>'));

            // Prevent overwrite of explicit target
            $this->assertEquals('<a href="http://example.com" target="_self">link</a>', WpLink::content('<a href="http://example.com" target="_self">link</a>'));
            $this->assertEquals('<a href="http://external.com" target="_blank">link</a>', WpLink::content('<a href="http://external.com" target="_blank">link</a>'));

            // Invalid markup should be returned unproccessed
            $invalid = 'foo<bar<narf--"xxx>test<foo';
            $this->assertEquals($invalid, WpLink::content($invalid));
        }
    }
}
