<?php
// helpers.php

// Base path definition
define('BASE_PATH', __DIR__);

// Base URL definition
$scriptName = $_SERVER['SCRIPT_NAME'];
$dirName = dirname($scriptName);
// If dirName is just /, set to empty string to avoid double slashes// If dirName is backslash (windows default sometimes), convert to forward slash
$dirName = str_replace('\\', '/', $dirName);
if ($dirName === '/') {
    $dirName = '';
}
define('BASE_URL', rtrim($dirName, '/'));

/**
 * Get full URL for a path
 */
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Load JSON data from the data directory
 */
function load_json($filename) {
    // If running via built-in server, avoid caching issues with file_get_contents sometimes
    clearstatcache();
    $path = BASE_PATH . '/data/' . $filename . '.json';
    $defaultPath = BASE_PATH . '/data/' . $filename . '.default.json';

    if (file_exists($path)) {
        $json = file_get_contents($path);
        return json_decode($json, true);
    } elseif (file_exists($defaultPath)) {
        // Copy default to live
        copy($defaultPath, $path);
        $json = file_get_contents($path);
        return json_decode($json, true);
    }
    return [];
}

/**
 * Save JSON data to the data directory
 */
function save_json($filename, $data) {
    $path = BASE_PATH . '/data/' . $filename . '.json';
    // JSON_PRETTY_PRINT for readability, JSON_UNESCAPED_UNICODE for Turkish characters
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($path, $json);
}

/**
 * Slugify a string
 */
function slugify($text) {
    // Turkish character replacement
    $tr = ['ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç'];
    $en = ['s','s','i','i','i','g','g','u','u','o','o','c','c'];
    $text = str_replace($tr, $en, $text);
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

/**
 * Handle File Upload
 * Returns relative path or null on failure
 */
function upload_file($file, $subDir = 'uploads') {
    if (!isset($file['error']) || is_array($file['error'])) {
        return null;
    }

    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = BASE_PATH . '/images/' . $subDir;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return '/images/' . $subDir . '/' . $filename;
        }
    }
    return null;
}

// Global Settings
$settings = load_json('settings');
$menu = load_json('menu');
$services = load_json('services');
$hero = load_json('hero');
$sectors = load_json('sectors');

/**
 * Get nested array value safely
 */
function get_value($array, $key, $default = '') {
    $keys = explode('.', $key);
    foreach ($keys as $k) {
        if (isset($array[$k])) {
            $array = $array[$k];
        } else {
            return $default;
        }
    }
    return is_array($array) ? $array : $array;
}

/**
 * Render Header
 */
function render_header() {
    global $settings, $menu, $services;
    include BASE_PATH . '/includes/header.php';
}

/**
 * Render Footer
 */
function render_footer() {
    global $settings, $menu, $services, $sectors;
    include BASE_PATH . '/includes/footer.php';
}

/**
 * Adjust Brightness of Hex Color
 * @param string $hexCode Input hex color
 * @param int $adjustPercent Percentage to adjust (-100 to 100)
 * @return string New hex color
 */
function adjustBrightness($hexCode, $adjustPercent) {
    $hexCode = ltrim($hexCode, '#');

    if (strlen($hexCode) == 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));

    foreach ($hexCode as & $color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount = ceil($adjustableLimit * $adjustPercent);

        $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    return '#' . implode($hexCode);
}

/**
 * Get System Version
 */
function get_system_version() {
    $version = '1.0.0';
    $versionFile = BASE_PATH . '/version.txt';
    if (file_exists($versionFile)) {
        $version = trim(file_get_contents($versionFile));
    }

    // Try to get git commit hash
    if (is_dir(BASE_PATH . '/.git') && function_exists('shell_exec')) {
        $gitShort = @shell_exec('cd ' . BASE_PATH . ' && git rev-parse --short HEAD');
        if ($gitShort) {
            $version .= ' (Build: ' . trim($gitShort) . ')';
        }
    }
    
    return $version;
}
?>
