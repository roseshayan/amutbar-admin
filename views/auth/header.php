<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../includes/settings.php';

$siteLogoPath = (string)settings_get('site.logo_path', '');
$authLogoHref = $siteLogoPath !== '' ? $siteLogoPath : 'assets/images/brand-logos/logo.png';
?>
<!DOCTYPE html>
<html lang="fa" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
      data-header-styles="light" data-menu-styles="light" data-toggled="close" dir="rtl">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="پنل مدیریت آموت اپ">
    <meta name="Author" content="Shayan Namayandeh - namayandeshayan@gmail.com - KFMY Company">
    <!-- TITLE -->
    <title>پنل مدیریت آموت اپ</title>
    <!-- FAVICON -->
    <?php
    $faviconPath = (string)settings_get('site.favicon_path', '');
    $faviconHref = $faviconPath !== '' ? $faviconPath : 'assets/images/brand-logos/favicon.ico';
    ?>
    <link rel="icon" href="<?= htmlspecialchars($faviconHref, ENT_QUOTES, 'UTF-8') ?>" type="image/x-icon">
    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/libs/bootstrap/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/vendor/sweetalert2/sweetalert2.min.css">
    <!-- STYLES CSS -->
    <link href="assets/css/styles.min.css" rel="stylesheet">
    <!-- ICONS CSS -->
    <link href="assets/icon-fonts/icons.css" rel="stylesheet">
</head>

<body class="authentication-background authenticationcover-background position-relative" id="particles-js">

<div class="container">
    <div class="row justify-content-center authentication authentication-basic align-items-center h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
            <div class="mb-3 d-flex justify-content-center auth-logo">
                <a href="index.php">
                    <img src="<?= htmlspecialchars($authLogoHref, ENT_QUOTES, 'UTF-8') ?>" alt="logo" class="desktop-dark">
                </a>
            </div>
            <div class="card custom-card my-4 border z-3 position-relative">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                                        <span class="auth-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 64 64" id="password"><path
                                                        fill="#6446fe"
                                                        d="M59,8H5A1,1,0,0,0,4,9V55a1,1,0,0,0,1,1H59a1,1,0,0,0,1-1V9A1,1,0,0,0,59,8ZM58,54H6V10H58Z"
                                                        class="color1d1f47 svgShape"></path><path fill="#6446fe"
                                                                                                  d="M36,35H28a3,3,0,0,1-3-3V27a3,3,0,0,1,3-3h8a3,3,0,0,1,3,3v5A3,3,0,0,1,36,35Zm-8-9a1,1,0,0,0-1,1v5a1,1,0,0,0,1,1h8a1,1,0,0,0,1-1V27a1,1,0,0,0-1-1Z"
                                                                                                  class="color0055ff svgShape"></path><path
                                                        fill="#6446fe"
                                                        d="M36 26H28a1 1 0 0 1-1-1V24a5 5 0 0 1 10 0v1A1 1 0 0 1 36 26zm-7-2h6a3 3 0 0 0-6 0zM32 31a1 1 0 0 1-1-1V29a1 1 0 0 1 2 0v1A1 1 0 0 1 32 31z"
                                                        class="color0055ff svgShape"></path><path fill="#6446fe"
                                                                                                  d="M59 8H5A1 1 0 0 0 4 9v8a1 1 0 0 0 1 1H20.08a1 1 0 0 0 .63-.22L25.36 14H59a1 1 0 0 0 1-1V9A1 1 0 0 0 59 8zm-1 4H25l-.21 0a1.09 1.09 0 0 0-.42.2L19.73 16H6V10H58zM50 49H14a1 1 0 0 1-1-1V39a1 1 0 0 1 1-1H50a1 1 0 0 1 1 1v9A1 1 0 0 1 50 49zM15 47H49V40H15z"
                                                                                                  class="color1d1f47 svgShape"></path><circle
                                                        cx="19.5" cy="43.5" r="1.5" fill="#6446fe"
                                                        class="color0055ff svgShape"></circle><circle cx="24.5"
                                                                                                      cy="43.5" r="1.5"
                                                                                                      fill="#6446fe"
                                                                                                      class="color0055ff svgShape"></circle><circle
                                                        cx="29.5" cy="43.5" r="1.5" fill="#6446fe"
                                                        class="color0055ff svgShape"></circle><circle cx="34.5"
                                                                                                      cy="43.5" r="1.5"
                                                                                                      fill="#6446fe"
                                                                                                      class="color0055ff svgShape"></circle><circle
                                                        cx="39.5" cy="43.5" r="1.5" fill="#6446fe"
                                                        class="color0055ff svgShape"></circle><circle cx="44.5"
                                                                                                      cy="43.5" r="1.5"
                                                                                                      fill="#6446fe"
                                                                                                      class="color0055ff svgShape"></circle><path
                                                        fill="#6446fe"
                                                        d="M60 9a1 1 0 0 0-1-1H28.81l2.37-2.37A19.22 19.22 0 0 1 60 31zM35.19 56l-2.37 2.37A19.22 19.22 0 0 1 4 33V55a1 1 0 0 0 1 1z"
                                                        opacity=".3" class="color0055ff svgShape"></path></svg>
                                        </span>
                        </div>