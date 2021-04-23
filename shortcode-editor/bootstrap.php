<?php
/*
Plugin Name: RB: Shortcode Editor
Description: Добавление шорткодов с кнопками в редакторе
*/

defined('RB_SHORTCODE_PATH') || define('RB_SHORTCODE_PATH', __DIR__);
defined('RB_SHORTCODE_URL') || define('RB_SHORTCODE_URL', plugins_url('', __FILE__));

/**
 * Autoload PSR-4
 */
spl_autoload_register(function ($class) {
    $prefix = 'RB\\WpShortCodeEditor\\';

    // base directory for the namespace prefix
    $baseDir = __DIR__ . '/classes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);

    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

add_action('init', function () {
    new \RB\WpShortCodeEditor\AddBet\Controller();
    new \RB\WpShortCodeEditor\AddBanner\Controller();
    new \RB\WpShortCodeEditor\RbBet\Controller();
});

