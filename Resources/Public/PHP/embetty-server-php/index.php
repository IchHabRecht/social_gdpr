<?php

const APP_NAME = 'embetty-server-php';
const APP_VERSION = '0.1';
const APP_USER_AGENT = APP_NAME.'/'.APP_VERSION;

const APP_CURL_TIMEOUT = 5;
const APP_CURL_CONNECTTIMEOUT = 2;
const APP_CURL_MAX_REDIRECTS = 3;
const APP_CURL_MAX_RESPONSE_SIZE = 1024 * 1024; // 1mb proxy response max

const APP_ROUTE_VIMEO = '#^/video/vimeo/(?P<id>[0-9]+)#';
const APP_ROUTE_YOUTUBE = '#^/video/youtube/(?P<id>[a-zA-Z0-9_-]+)#';

const APP_YOUTUBE_URL_HIGHRES = 'https://img.youtube.com/vi/##VID##/maxresdefault.jpg';
const APP_YOUTUBE_URL_DEFAULT = 'https://img.youtube.com/vi/##VID##/hqdefault.jpg';

const APP_VIMEO_URL_DEFAULT = 'https://vimeo.com/api/v2/video/##VID##.json';

// check whether the given url exists by doing a HEAD request
function urlExists(string $url): bool
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request
    curl_setopt($ch, CURLOPT_USERAGENT, APP_USER_AGENT);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, APP_CURL_CONNECTTIMEOUT);
    curl_setopt($ch, CURLOPT_TIMEOUT, APP_CURL_TIMEOUT);
    if (curl_exec($ch) === false) {
        curl_close($ch);
        return false;
    }
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code === 200;
}

// return a thumbnail url for the given youtube video identifier
function youtubePosterImageUrl(string $id): string
{
    $hires = str_replace('##VID##', $id, APP_YOUTUBE_URL_HIGHRES);
    if (urlExists($hires)) {
        return $hires;
    }
    return str_replace('##VID##', $id, APP_YOUTUBE_URL_DEFAULT);
}

// return a thumbnail url for the given vimeo video identifier
function vimeoPosterImageUrl(string $id): string
{
    return str_replace('##VID##', $id, APP_VIMEO_URL_DEFAULT);
}

// get response body content for given url (requested content_type is json by default)
function fetchUrl(string $url, string $content_type = 'application/json'): string
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, APP_USER_AGENT);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, APP_CURL_CONNECTTIMEOUT);
    curl_setopt($ch, CURLOPT_TIMEOUT, APP_CURL_TIMEOUT);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Content-Type: '.($content_type?:'application/json') ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, APP_CURL_MAX_REDIRECTS);
    $content = curl_exec($ch);
    if ($content === false) {
        curl_close($ch);
        return '';
    }
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code === 200) {
        return $content;
    }
    return '';
}

// proxy given URL by forwarding the response (unless too large, not an image etc.)
function proxy(string $url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, APP_USER_AGENT);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, APP_CURL_CONNECTTIMEOUT);
    curl_setopt($ch, CURLOPT_TIMEOUT, APP_CURL_TIMEOUT);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, APP_CURL_MAX_REDIRECTS);
    curl_setopt($ch, CURLOPT_BUFFERSIZE, 12800);
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function ($download_size, $downloaded, $upload_size, $uploaded) {
        return ($downloaded > APP_CURL_MAX_RESPONSE_SIZE) ? 1 : 0;
    });
    $content = curl_exec($ch);
    curl_close($ch);
    $headers = [];
    $response_parts = explode("\r\n\r\n", $content, 2);
    // dirty fix for additional header by proxy, remove that first response parts
    if (strlen($response_parts[0]) < 50) {
        array_shift($response_parts);
    }
    $header_lines = explode("\r\n", $response_parts[0]);
    foreach ($header_lines as $header_line) {
        $header_pieces = explode(': ', $header_line);
        if (count($header_pieces) === 2) {
            $headers[strtolower($header_pieces[0])] = trim($header_pieces[1]);
        }
    }
    if (array_key_exists('location', $headers)) {
        echo 'Too many redirects.';
        header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
        exit;
    }
    $ct = $headers['content-type'] ?? '';
    if (preg_match('#image/png|image/jpe?g|image/webp#', $ct) !== 1) {
        echo 'Returned image format not supported.';
        header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
        exit;
    }
    header('Content-Type: ' . $ct);
    if (array_key_exists('content-length', $headers)) {
        header('content-length: ' . $headers['content-length']);
    }
    if (array_key_exists('expires', $headers)) {
        header('expires: ' . $headers['expires']);
    }
    if (array_key_exists('cache-control', $headers)) {
        header('cache-control: ' . $headers['cache-control']);
    }
    if (array_key_exists('last-modified', $headers)) {
        header('last-modified: ' . $headers['last-modified']);
    }
    echo $response_parts[1];
}

// proxy a thumbnail for a given youtube identifier
function handleYoutube(string $id)
{
    proxy(youtubePosterImageUrl($id));
    exit;
}

// proxy a thumbnail for a given vimeo identifier
function handleVimeo(string $id = '')
{
    $json = json_decode(fetchUrl(vimeoPosterImageUrl($id)), true);
    if (!is_array($json) || empty($json[0]['thumbnail_large'])) {
        echo 'Returned json invalid.';
        header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
        exit;
    }
    proxy($json[0]['thumbnail_large']);
    exit;
}

// skip actual verification and act as if nothing has changed since the last image request
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
    header($_SERVER["SERVER_PROTOCOL"].' 304 Not Modified');
    exit;
}

//
// main routing :D
//

$path = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'embetty-server-php') + 18);

if (strpos($path, 'video/vimeo') !== false && (preg_match(APP_ROUTE_VIMEO, $path, $matches) === 1)) {
    handleVimeo($matches['id'] ?? null);
} elseif (strpos($path, 'video/youtube') !== false && (preg_match(APP_ROUTE_YOUTUBE, $path, $matches) === 1)) {
    handleYoutube($matches['id'] ?? null);
} elseif ($path === '/version') {
    echo APP_NAME.' '.APP_VERSION;
    header($_SERVER["SERVER_PROTOCOL"].' 200 Ok');
    exit;
}

// no route matched
echo "Nothing to see here.";
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
exit;
