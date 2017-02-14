<?php
namespace Onvardgmbh;

class WpLink
{
    public static function target(String $uri)
    {
        $siteUrl = preg_replace('/^(https?:)?\/\//', '', site_url());
        // If the URI starts with a schema, make sure the domain is not the current site
        $isExternal = preg_match('/^(https?:)?\/\/(?!' . str_replace('/', '\\/', $siteUrl) . ')/', $uri);
        $isExternal = apply_filters('link_target', $isExternal, $uri);
        return $isExternal ? 'target="_blank"' : '';
    }
}
