<?php

include __DIR__ . '/parameter.php';
include __DIR__ . '/message.php';

/**
 * 結果をjsonで返却する
 *
 * @param  array resultArray 返却値
 * @return string jsonで表現されたレスポンス
 * @author nishijima
 **/
function returnJson($resultArray) {
    global $contentType01;

    if(array_key_exists('callback', $_GET)) {
        $json = $_GET['callback'] . "(" . json_encode($resultArray) . ");";
    } else {
        $json = json_encode($resultArray);
    }
    header($contentType01);
    echo $json;
    exit(0);
}

/**
 * Error結果をjsonで返却する
 *
 * @param  array resultArray 返却値
 * @return string jsonで表現されたレスポンス
 * @author nishijima
 **/
function returnErrorJson($resultArray) {
    global $msg_http_400_error001, $contentType01;

    $json = json_encode($resultArray);
    header($msg_http_400_error001);
    header($contentType01);
    echo $json;
    exit(0);
}


// HTML XSS対策
function htmlEnc($value) {
    return htmlspecialchars($value,ENT_QUOTES, 'UTF-8');
}

// DBO作成
function createDbo() {
    global $datasource, $dbUser,$dbPass;

    return
        new PDO(
            $datasource,
            $dbUser,
            $dbPass,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            )
    );
}

// 1週間後の日付取得（年月日）
function getDateYmdAfterOneWeek() {
    $date = new DateTime(date('Ymd'));
    $date->add(new DateInterval('P6D'));
    return $date->format('Ymd');
}

// 現在の日付年月日を取得
function getDateYmd() {
//  return date('Ymd');
//  TODO テスト用
    return '20160716';
}

// エラーメッセージ用連想配列作成
function getErrorMessageArray($message) {
    return array(
        "message" => $message
    );
}

?>