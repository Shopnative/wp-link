<?php
require_once('vendor/autoload.php');

define('WP_DEBUG', true);

function site_url() : String
{
    global $siteUrl;
    return !empty($siteUrl) ? $siteUrl : 'http://example.com';
}

function apply_filters(String $tag, $value, ...$vars)
{
    //TODO test mock filter
    return $value;
}
