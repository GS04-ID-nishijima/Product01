<?php

include '../../include/func.php';
include '../../include/message.php';

/**
 * 開催情報リストを返す
 *
 * @param mode: 1:表示地図内リスト取得、2:全件リスト取得
 * @param withinOneWeekFlag: 0:すべて、1:一週間以内
 * @param startingPointLatitudo: 表示地図の左上始点の緯度
 * @param startingPointLongitude: 表示地図の左上始点の経度
 * @param endPointLatitudo: 表示地図の右下終点の緯度
 * @param endPointLongitude: 表示地図の右下終点の経度
 * @return hostInfoList
 *             holdingDateYmd
 *             hostList
 *                 hostGroupId
 *                 hostName
 *                 placeName
 *                 holdingSchedule
 *                 latitudo
 *                 longitude
 *                 eventName
 *                 branchScale
 *
 * @author nishijima
 **/
error_log('....', 0);
error_log(var_dump($_REQUEST), 0);
$mode = (string)filter_input(INPUT_GET, $_REQUEST['mode']);
$weekFlag = (string)filter_input(INPUT_GET, $_REQUEST['withinOneWeekFlag']);
$strLati = (double)filter_input(INPUT_GET, $_REQUEST['startingPointLatitudo']);
$strLong = (double)filter_input(INPUT_GET, $_REQUEST['startingPointLongitude']);
$endLati = (double)filter_input(INPUT_GET, $_REQUEST['endPointLatitudo']);
$endLong = (double)filter_input(INPUT_GET, $_REQUEST['endPointLongitude']);

try {
    // 必須チェック
    if($mode || $weekFlag) {
        throw new RuntimeException($msg_hostlistApi_parameter_error001);
    }

    // 表示地図内リスト取得の場合は、緯度経度情報がすべてセットされていることをチェック
    if($mode === '1' && !(empty($strLati) || empty($strLong) || empty($endLati) || empty($endLong))) {
        throw new RuntimeException($msg_hostlistApi_parameter_error002);
    }
 
    $pdo = createDbo();
    $stmt = NULL;

error_log('start', 0);
error_log($mode, 0);
error_log($weekFlag, 0);

    if($mode === '1') {
error_log('start1', 0);
        // 表示地図内開催情報取得
        if($weekFlag === "0") {
            // 開催日制限なし
            $stmt = $pdo->prepare($getHostListScopeMap);

            $stmt->bindValue(':current_date_ymd', date('Ymd'));
            $stmt->bindValue(':strLati', $strLati, PDO::PARAM_STR);
            $stmt->bindValue(':strLong', $strLong, PDO::PARAM_STR);
            $stmt->bindValue(':endLati', $endLati, PDO::PARAM_STR);
            $stmt->bindValue(':endLong', $endLong, PDO::PARAM_STR);
error_log(1, 0);
error_log(var_dump($stmt), 0);
            $stmt->execute();
        } else if($weekFlag === "1") {
            // 1週間以内に開催
            $stmt = $pdo->prepare($getHostListScopwMapOneWeek);

            $stmt->bindValue(':current_date_ymd', date('Ymd'));
            $stmt->bindValue(':to_date_ymd', getDateYmdAfterOneWeek());
            $stmt->bindValue(':strLati', $strLati, PDO::PARAM_STR);
            $stmt->bindValue(':strLong', $strLong, PDO::PARAM_STR);
            $stmt->bindValue(':endLati', $endLati, PDO::PARAM_STR);
            $stmt->bindValue(':endLong', $endLong, PDO::PARAM_STR);
error_log(2, 0);
error_log(var_dump($stmt), 0);
            $stmt->execute();
        }
    } else if($mode === "2") {
        // 全件取得
        if($weekFlag === "0") {
            // 開催日制限なし
            $stmt = $pdo->prepare($getHostList);

            $stmt->bindValue(':current_date_ymd', date('Ymd'));
error_log(3, 0);
error_log(var_dump($stmt), 0);
            $stmt->execute();
        } else if($weekFlag === "1") {
            // 1週間以内に開催
            $stmt = $pdo->prepare($getHostListOneWeek);

            $stmt->bindValue(':current_date_ymd', date('Ymd'));
            $stmt->bindValue(':to_date_ymd', getDateYmdAfterOneWeek());
error_log(3, 0);
error_log(var_dump($stmt), 0);
            $stmt->execute();
        }
    }

    $hostInfoList = array();
    $hostList = array();
    $tmpHoldingDateYmd = NULL;

error_log(var_dump($stmt), 0);

    foreach((array)$stmt as $row) {
//    foreach($stmt->fetch(PDO::FETCH_ASSOC as $row)) {
var_dump($row);
        if(!is_null($tmpHoldingDateYmd)) {
echo "first";
            $tmpHoldingDateYmd = $row['holding_date_ymd'];
        } else if($tmpHoldingDateYmd !== $row['holding_date_ymd']) {
echo "not first";
            $hostInfoList[] = array(
                'holdingDateYmd'=>$tmpHoldingDateYmd,
                'hostList'=>$hostList
            );
            $hostList = array();
            $tmpHoldingDateYmd = $row['holding_date_ymd'];
        }

        $host = array();
        $host[] = array(
            'hostGroupId'=>$row['host_group_id'],
            'hostName'=>$row['host_name'],
            'placeName'=>$row['place_name'],
            'holdingSchedule'=>$row['holding_schedule'],
            'latitudo'=>$row['latitudo'],
            'longitude'=>$row['longitude'],
//            イベント情報は一旦後回し
//            'eventName'=>$row['host_group_id']
            'branchScale'=>$row['branch_scale']
        );

        $hostList[] = $host;
    }

    $hostInfoList[] = array(
        'holdingDateYmd'=>$tmpHoldingDateYmd,
        'hostList'=>$hostList
    );

    returnJson($hostInfoList);
} catch(RuntimeException $e) {
    header($msg_http_404_error001);
    exit(0);
} catch(Exception $e) {
    header($msg_http_500_error001);
    exit(0);
}

?>