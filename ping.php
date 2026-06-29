<?php
require_once 'connect.php';
// connect.php automatically calls $ajax->updateOnline(IP) to refresh lastOnline time.
// We explicitly refresh the page to Giriş Sayfası.
$ajax->pageUpdate(IP, 'Giriş Sayfası');
echo "ok";
