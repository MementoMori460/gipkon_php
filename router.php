<?php
// router.php
require_once __DIR__ . '/functions.php';

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Normalize path if in subdirectory
$baseUrl = defined('BASE_URL') ? BASE_URL : '';
if ($baseUrl && strpos($path, $baseUrl) === 0) {
    $path = substr($path, strlen($baseUrl));
}

// Ensure path starts with /
if (empty($path)) {
    $path = '/';
}

// Serve static assets directly
// Check if file exists relative to document root (or base path) before returning false to let PHP handle it?
// Actually PHP built-in server handles existing files automatically before router.php if we return false?
// But router.php is passed as argument, so it handles everything.
// Better check extensions.
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|svg|ico|pdf|webp)$/', $path)) {
    return false;
}

// DEBUG LOGGING
// file_put_contents('router_debug.txt', date('Y-m-d H:i:s') . " URI: " . $_SERVER['REQUEST_URI'] . " PATH: " . $path . "\n", FILE_APPEND);

// Remove trailing slash if it's not root
if ($path != '/' && substr($path, -1) == '/') {
    $path = substr($path, 0, -1);
}

// DEBUG: Uncomment to trace
// error_log("Request URI: " . $_SERVER['REQUEST_URI']);
// error_log("Parsed Path: " . $path);


// Router Logic
switch ($path) {
    case '/':
    case '/index.php':
        require __DIR__ . '/pages/index.php';
        break;

    // Contact
    case '/iletisim':
        require __DIR__ . '/pages/contact.php';
        break;
        
    // Hizmetler (Services)
    case '/hizmetler':
        require __DIR__ . '/pages/services.php';
        break;

    // Solutions (Cozumler)
    case '/cozumler':
        require __DIR__ . '/pages/solutions.php';
        break;

    // Projects (Projeler)
    case '/projeler':
        require __DIR__ . '/pages/projects.php';
        break;
    
    // Service Network (Servis)
    case '/servis':
        require __DIR__ . '/pages/service-network.php';
        break;

    // Service Request (Hizmet Talebi)
    case '/hizmet-talebi':
        require __DIR__ . '/pages/request.php';
        break;

    // References (Top Level - Redirects to same file as Corporate/References)
    case '/referanslar':
        require __DIR__ . '/pages/corporate/references.php';
        break;

    // Corporate Subpages
    case '/kurumsal/hakkimizda':
        require __DIR__ . '/pages/corporate/about.php';
        break;
    case '/kurumsal/tanitim-videomuz':
        require __DIR__ . '/pages/corporate/video.php';
        break;
    case '/kurumsal/kataloglarimiz':
        require __DIR__ . '/pages/corporate/catalogs.php';
        break;
    case '/kurumsal/referanslar':
        require __DIR__ . '/pages/corporate/references.php';
        break;
    case '/kurumsal/sss':
        require __DIR__ . '/pages/corporate/faq.php';
        break;
    case '/kurumsal/insan-kaynaklari':
        require __DIR__ . '/pages/corporate/hr.php';
        break;

    // Admin Routes
    case '/admin':
    case '/admin/dashboard':
        require __DIR__ . '/pages/admin/dashboard.php';
        break;
    case '/admin/login':
        require __DIR__ . '/pages/admin/login.php';
        break;
    case '/admin/logout':
        session_start();
        session_destroy();
        header('Location: /admin/login');
        exit;
        break;

    // Admin Pages
// Admin Pages
case '/admin/services':
    require __DIR__ . '/pages/admin/services.php';
    break;
case '/admin/projects':
    require __DIR__ . '/pages/admin/projects.php';
    break;
case '/admin/references':
    require __DIR__ . '/pages/admin/references.php';
    break;
case '/admin/settings':
    require __DIR__ . '/pages/admin/settings.php';
    break;
case '/admin/theme':
    require __DIR__ . '/pages/admin/theme.php';
    break;
case '/admin/sectors':
    require __DIR__ . '/pages/admin/sectors.php';
    break;
case '/admin/hero':
    require __DIR__ . '/pages/admin/hero.php';
    break;
case '/admin/menu':
    require __DIR__ . '/pages/admin/menu.php';
    break;
case '/admin/media':
    require __DIR__ . '/pages/admin/media.php';
    break;
case '/admin/backup':
    require __DIR__ . '/pages/admin/backup.php';
    break;
case '/admin/catalogs':
    require __DIR__ . '/pages/admin/catalogs.php';
    break;
case '/admin/faq':
    require __DIR__ . '/pages/admin/faq.php';
    break;
case '/admin/video':
    require __DIR__ . '/pages/admin/video.php';
    break;
case '/admin/update':
    require __DIR__ . '/pages/admin/update.php';
    break;
    
    default:
        // Dynamic Routes or 404
        if (strpos($path, '/hizmetler/') === 0) {
            // Extract slug
            $parts = explode('/', trim($path, '/'));
            if(count($parts) >= 2) {
                $_GET['slug'] = $parts[1];
                require __DIR__ . '/pages/service-detail.php';
                break;
            }
        }

        // Solutions Detail: /cozumler/slug
        if (strpos($path, '/cozumler/') === 0) {
            $parts = explode('/', trim($path, '/'));
            if(count($parts) >= 2) {
                $_GET['slug'] = $parts[1];
                require __DIR__ . '/pages/solution-detail.php';
                break;
            }
        }

        // Project Detail: /projeler/project-slug
        if (strpos($path, '/projeler/') === 0) {
             $parts = explode('/', trim($path, '/'));
             if(count($parts) >= 2) {
                 $_GET['slug'] = $parts[1];
                 require __DIR__ . '/pages/project-detail.php';
                 break;
             }
        }

        // 404
        http_response_code(404);
        echo "404 Not Found";
        break;
}
?>
