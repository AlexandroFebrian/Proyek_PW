<?php
    if (version_compare(PHP_VERSION, '5.4', '<')) {
        throw new Exception('PHP version >= 5.4 required');
    }

    // Check PHP Curl & json decode capabilities.
    if (!function_exists('curl_init') || !function_exists('curl_exec')) {
        throw new Exception('Midtrans needs the CURL PHP extension.');
    }
    if (!function_exists('json_decode')) {
        throw new Exception('Midtrans needs the JSON PHP extension.');
    }

    // Configurations
    require_once 'midtrans/Config.php';

    // Midtrans API Resources
    require_once 'midtrans/Transaction.php';

    // Plumbing
    require_once 'midtrans/ApiRequestor.php';
    require_once 'midtrans/Notification.php';
    require_once 'midtrans/CoreApi.php';
    require_once 'midtrans/Snap.php';

    // Sanitization
    require_once 'midtrans/Sanitizer.php';
?>