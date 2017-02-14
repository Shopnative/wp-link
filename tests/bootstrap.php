<?php
require_once('vendor/autoload.php');

function site_url() : String
{
    global $siteUrl;
    return !empty($siteUrl) ? $siteUrl : 'http://example.com';
}

function apply_filters(String $tag, $value)
{
    //TODO test mock filter
    return $value;
}
