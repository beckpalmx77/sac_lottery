<?php
var_dump(function_exists('curl_init'));

if (defined('CURLOPT_CONNECTTIMEOUT')) {
    echo 'CURLOPT_CONNECTTIMEOUT is defined';
} else {
    echo 'CURLOPT_CONNECTTIMEOUT is NOT defined';
}
