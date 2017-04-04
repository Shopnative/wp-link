<?php
namespace Onvardgmbh;

class WpLink
{
    /**
     * Check if a specific URI is an external link.
     *
     * @param  string  $uri
     * @return bool
     */
    public static function external(string $uri)
    {
        $siteUrl = preg_replace('/^(https?:)?\/\//', '', site_url());
        // If the URI starts with a schema, make sure the domain is not the current site
        $isExternal = preg_match('/^(https?:)?\/\/(?!' . str_replace('/', '\\/', $siteUrl) . ')/', $uri);
        $isExternal = apply_filters('link_target', $isExternal, $uri);
        return $isExternal;
    }

    /**
     * Return the target attribute for a link to the given URI.
     *
     * @param  string  $uri
     * @return string
     */
    public static function target(string $uri)
    {
        return static::external($uri) ? 'target="_blank"' : '';
    }

    /**
     * Add target attributes to all links in the passed HTML.
     *
     * @param  string $content
     * @return string
     */
    public static function content($content)
    {
        $document = new \DOMDocument();
        $success = $document->loadHTML($content);
        if (!$success) {
            error_log('Failed to load HTML!');
            return $content;
        }

        foreach ($document->getElementsByTagName('a') as $a) {
            if (static::external($a->getAttribute('href'))) {
                $a->setAttribute('target', '_blank');
            }
        }

        return $document->saveHTML($document->documentElement->firstChild->firstChild);
    }
}
