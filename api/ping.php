<?php
// Prevent Vercel CDN and browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'connect.php';
// connect.php automatically calls $ajax->updateOnline(IP) to refresh lastOnline time.
// We explicitly refresh the page to Giriş Sayfası.
$ajax->pageUpdate(IP, 'Giriş Sayfası');
echo "ok";

