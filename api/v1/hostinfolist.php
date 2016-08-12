<?php

include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催情報リストを返す
 *
 * @param mode: 1:表示地図内リスト取得、2:全件リスト取得
 * @param rangeMode: 1:一週間以内、2:すべて
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
$rangeMode = (string)filter_input(INPUT_GET, 'rangeMode');
$strLati = (double)filter_input(INPUT_GET, 'startingPointLatitude');
$strLong = (double)filter_input(INPUT_GET, 'startingPointLongitude');
$endLati = (double)filter_input(INPUT_GET, 'endPointLatitude');
$endLong = (double)filter_input(INPUT_GET, 'endPointLongitude');

define("MODE_SCOPEMAP", "1");
define("MODE_ALL", "2");
define("RANGEMODE_ONE", "1");
define("RANGEMODE_ALL", "2");

// 必須チェック
if(empty($mode) || empty($rangeMode)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOSTINFOLISTAPI_PARAMETER_ERROR001));
}

// パラメータmodeの値チェック
if($mode !== MODE_SCOPEMAP && $mode !== MODE_ALL){
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOSTINFOLISTAPI_PARAMETER_ERROR003));
}

// パラメータrangeModeの値チェック
if($rangeMode !== MODE_SCOPEMAP && $rangeMode !== MODE_ALL){
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOSTINFOLISTAPI_PARAMETER_ERROR004));
}

// 表示地図内リスト取得の場合は、緯度経度情報がすべてセットされていることをチェック
if($mode === MODE_SCOPEMAP && (empty($strLati) || empty($strLong) || empty($endLati) || empty($endLong))) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOSTINFOLISTAPI_PARAMETER_ERROR002));
}

try {
    $pdo = createDbo();
    $stmt = NULL;

    if($mode === MODE_SCOPEMAP) {
        // 表示地図内開催情報取得
        if($rangeMode === RANGEMODE_ONE) {
            // 1週間以内に開催
            $stmt = $pdo->prepare($getHostListScopwMapOneWeek);

            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt->bindValue(':to_date_ymd', getDateYmdAfterOneWeek(), PDO::PARAM_STR);
            $stmt->bindValue(':strLati', $strLati, PDO::PARAM_STR);
            $stmt->bindValue(':strLong', $strLong, PDO::PARAM_STR);
            $stmt->bindValue(':endLati', $endLati, PDO::PARAM_STR);
            $stmt->bindValue(':endLong', $endLong, PDO::PARAM_STR);
            $stmt->execute();
        } else if($rangeMode === RANGEMODE_ALL) {
            // 開催日制限なし
            $stmt = $pdo->prepare($getHostListScopeMap);

            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt->bindValue(':strLati', $strLati, PDO::PARAM_STR);
            $stmt->bindValue(':strLong', $strLong, PDO::PARAM_STR);
            $stmt->bindValue(':endLati', $endLati, PDO::PARAM_STR);
            $stmt->bindValue(':endLong', $endLong, PDO::PARAM_STR);
            $stmt->execute();
        }
    } else if($mode === MODE_ALL) {
        // 全件取得
        if($rangeMode === RANGEMODE_ONE) {
            // 1週間以内に開催
            $stmt = $pdo->prepare($getHostListOneWeek);

            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt->bindValue(':to_date_ymd', getDateYmdAfterOneWeek(), PDO::PARAM_STR);
            $stmt->execute();
        } else if($rangeMode === RANGEMODE_ALL) {
            // 開催日制限なし
            $stmt = $pdo->prepare($getHostList);

            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt->execute();
        }
    }

} catch(RuntimeException $e) {
    error_log($e, 0);
    header(MSG_HTTP_400_ERROR001);
    exit(0);
} catch(Exception $e) {
    error_log($e, 0);
    header(MSG_HTTP_500_ERROR001);
    exit(0);
}

$firstFlag = TRUE;
$hostInfoList = NULL;
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

    $keys = array_keys($row);
    $tempArray = array();

    foreach($keys as $key) {
        $tempArray[toCamelCase($key)] = $row[$key];
    }
    unset($tempArray['holdingDateYmd']);
    unset($tempArray['holdingTime']);

    $hostList[] = $tempArray;
}

// 取得データが0件の場合
if(count($hostInfoList) === 0 && count($hostList) === 0) {
    $hostInfoList[] = array();
    exitAsJson($hostInfoList);
} else {
    $hostInfoList[] = array(
        'holdingDateYmd'=>$holdingDateYmd,
        'hostList'=>$hostList
    );
}
error_log(print_r($hostInfoList, true));
$returnList[] = array(
    'hostInfoList'=>$hostInfoList
);

exitAsJson($returnList);

?>