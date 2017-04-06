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
        if (empty($content)) {
            return $content;
        }

        try {
            //$document = new \DOMDocument('1.0', 'UTF-8');
            $document = new \DOMDocument();
            $document->encoding = 'UTF-8';
            $success = $document->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            if (!$success) {
                throw new \Exception('Failed to load HTML!');
            }

            foreach ($document->getElementsByTagName('a') as $a) {
                if (static::external($a->getAttribute('href')) && !$a->hasAttribute('target')) {
                    $a->setAttribute('target', '_blank');
                }
            }

            $bodyInnerHTML = $document->saveHTML($document->documentElement->firstChild);
            return substr($bodyInnerHTML, 6, strlen($bodyInnerHTML) - 13); // Cut off <body> and </body>
        } catch (\Exception $e) {
            // If there was an error, return the content unprocessed
            return $content;
        }
    }
}
