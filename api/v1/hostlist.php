<?php

include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催情報リストを返す
 *
 * @param mode: 1:表示地図内リスト取得、2:全件リスト取得
 * @param withinOneWeekMode: 1:すべて、2:一週間以内
 * @param startingPointLatitude: 表示地図の左上始点の緯度
 * @param startingPointLongitude: 表示地図の左上始点の経度
 * @param endPointLatitude: 表示地図の右下終点の緯度
 * @param endPointLongitude: 表示地図の右下終点の経度
 * @return hostInfoList
 *             holdingDateYmd
 *             hostList
 *                 hostGroupId
 *                 hostGroupName
 *                 placeName
 *                 holdingSchedule
 *                 latitude
 *                 longitude
 *                 eventName
 *                 branchScale
 *
 * @author nishijima
 **/
$mode = (string)filter_input(INPUT_GET, 'mode');
$weekMode = (string)filter_input(INPUT_GET, 'withinOneWeekMode');
$strLati = (double)filter_input(INPUT_GET, 'startingPointLatitude');
$strLong = (double)filter_input(INPUT_GET, 'startingPointLongitude');
$endLati = (double)filter_input(INPUT_GET, 'endPointLatitude');
$endLong = (double)filter_input(INPUT_GET, 'endPointLongitude');

// 必須チェック
if(empty($mode) || empty($weekMode)) {
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error001));
}

// パラメータmodeの値チェック
if($mode !== '1' && $mode !== '2'){
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error003));
}

// パラメータwithinOneWeekModeの値チェック
if($weekMode !== '1' && $weekMode !== '2'){
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error004));
}

// 表示地図内リスト取得の場合は、緯度経度情報がすべてセットされていることをチェック
if($mode === '1' && (empty($strLati) || empty($strLong) || empty($endLati) || empty($endLong))) {
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error002));
}

try {
    $pdo = createDbo();
    $stmt = NULL;

    if($mode === '1') {
        // 表示地図内開催情報取得
        if($weekMode === "1") {
            // 開催日制限なし
            $stmt = $pdo->prepare($getHostListScopeMap);

            $stmt->bindValue(':current_date_ymd', getDateYmd());
            $stmt->bindValue(':strLati', $strLati, PDO::PARAM_STR);
            $stmt->bindValue(':strLong', $strLong, PDO::PARAM_STR);
            $stmt->bindValue(':endLati', $endLati, PDO::PARAM_STR);
            $stmt->bindValue(':endLong', $endLong, PDO::PARAM_STR);
            $stmt->execute();
        } else if($weekMode === "2") {
            // 1週間以内に開催
            $stmt = $pdo->prepare($getHostListScopwMapOneWeek);

            $stmt->bindValue(':current_date_ymd', getDateYmd());
            $stmt->bindValue(':to_date_ymd', getDateYmdAfterOneWeek());
            $stmt->bindValue(':strLati', $strLati, PDO::PARAM_STR);
            $stmt->bindValue(':strLong', $strLong, PDO::PARAM_STR);
            $stmt->bindValue(':endLati', $endLati, PDO::PARAM_STR);
            $stmt->bindValue(':endLong', $endLong, PDO::PARAM_STR);
            $stmt->execute();
        }
    } else if($mode === "2") {
        // 全件取得
        if($weekMode === "1") {
            // 開催日制限なし
            $stmt = $pdo->prepare($getHostList);

            $stmt->bindValue(':current_date_ymd', getDateYmd());
            $stmt->execute();
        } else if($weekMode === "2") {
            // 1週間以内に開催
            $stmt = $pdo->prepare($getHostListOneWeek);

            $stmt->bindValue(':current_date_ymd', getDateYmd());
            $stmt->bindValue(':to_date_ymd', getDateYmdAfterOneWeek());
            $stmt->execute();
        }
    }

} catch(RuntimeException $e) {
    error_log($e, 0);
    header($msg_http_404_error001);
    exit(0);
} catch(Exception $e) {
    error_log($e, 0);
    header($msg_http_500_error001);
    exit(0);
}

$firstFlag = TRUE;
$dataCnt = 0;
foreach($stmt as $row) {
    $nextHoldingDateYmd = $row['holding_date_ymd'];
    if($firstFlag) {
        $holdingDateYmd = $nextHoldingDateYmd;
        $firstFlag = FALSE;
    }

    if($holdingDateYmd !== $nextHoldingDateYmd) {
        $hostInfoList[] = array(
            'holdingDateYmd'=>$holdingDateYmd,
            'hostList'=>$hostList
        );

        $hostList = NULL;
        $holdingDateYmd = $nextHoldingDateYmd;
    }

    $host = array();
    $host[] = array(
        'hostGroupId'=>$row['host_group_id'],
        'hostGroupName'=>$row['host_group_name'],
        'placeName'=>$row['place_name'],
        'holdingSchedule'=>$row['holding_schedule'],
        'latitude'=>$row['latitude'],
        'longitude'=>$row['longitude'],
//            イベント情報は一旦後回し
//            'eventName'=>$row['host_group_id']
        'branchScale'=>$row['branch_scale']
    );

    $hostList[] = $host;
    $dataCnt += 1;
}

// 取得データが0件の場合
if($dataCnt ===0) {
    returnJson(getErrorMessageArray($msg_api_data_error001));
}

returnJson($hostInfoList);

?>