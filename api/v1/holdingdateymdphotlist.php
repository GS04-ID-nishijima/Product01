<?php

include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 写真リストを返す
 *
 * @param mode: 1:最新写真取得、2:開催団体写真取得、3:出店者写真取得
 * @param number: 取得枚数指定、未設定の場合は最大50枚
 * @param holdingDateYmd: 開催日（モードが開催団体、出店者写真取得のみ必須）
 * @param hostGroupId: 開催団体ID（モードが開催団体写真取得のみ必須）
 * @param branchPersonId: 出店者ID（モードが出店者写真取得のみ必須）
 * @return photoList（1:最新写真取得モード時の返却場合にセット、holdingDateYmdPhotoListはセットしない）
 *             hostGroupId
 *             hostGroupName
 *             branchPersonId
 *             branchPersonName
 *             photoId
 *             filepath
 *             filename
 *             reductionFilename
 *             thumbnailFilename
 *             comment
 *         holdingDateYmdPhotoList（2:開催団体写真取得、3:出店者写真取得時の返却場合にセット、photoListはセットしない）
 *             branchPersonId
 *             branchPersonName
 *             photoList
 *             photoId
 *             filepath
 *             filename
 *             reductionFilename
 *             thumbnailFilename
 *             comment
 *
 * @author nishijima
 **/
$mode = (string)filter_input(INPUT_GET, 'mode');
$number = (int)filter_input(INPUT_GET, 'number');
$holdingDateYmd = (string)filter_input(INPUT_GET, 'holdingDateYmd');
$hostGroupId = (int)filter_input(INPUT_GET, 'hostGroupId');
$branchPersonId = (int)filter_input(INPUT_GET, 'branchPersonId');

// 必須チェック
if(empty($mode)) {
    returnJson(getErrorMessageArray($msg_photolistApi_parameter_error001));
}

// パラメータmodeの値チェック
if($mode !== '1' && $mode !== '2'){
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error003));
}

// パラメータmodeの値チェック
if($mode !== '1' && $mode !== '2' && $mode !== '3'){
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error004));
}

// 開催団体写真取得の場合は、開催日、開催団体IDがすべてセットされていることをチェック
if($mode === '2' && (empty($holdingDateYmd) || empty($hostGroupId))) {
    returnJson(getErrorMessageArray($msg_photolistApi_parameter_error002));
}
// 出店者写真取得の場合は、開催日、出店者IDがすべてセットされていることをチェック
if($mode === '2' && (empty($holdingDateYmd) || empty($branchPersonId))) {
    returnJson(getErrorMessageArray($msg_photolistApi_parameter_error003));
}

try {
    $pdo = createDbo();
    $stmt = NULL;

    if($mode === '1') {
        // 表示地図内開催情報取得
        if($weekMode === "1") {
            // 開催日制限なし
            $stmt = $pdo->prepare($getHostListScopeMap);

            $stmt->bindValue(':current_date_ymd', date('Ymd'));
            $stmt->bindValue(':strLati', $strLati, PDO::PARAM_STR);
            $stmt->bindValue(':strLong', $strLong, PDO::PARAM_STR);
            $stmt->bindValue(':endLati', $endLati, PDO::PARAM_STR);
            $stmt->bindValue(':endLong', $endLong, PDO::PARAM_STR);
            $stmt->execute();
        } else if($weekMode === "2") {
            // 1週間以内に開催
            $stmt = $pdo->prepare($getHostListScopwMapOneWeek);

            $stmt->bindValue(':current_date_ymd', date('Ymd'));
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

            $stmt->bindValue(':current_date_ymd', date('Ymd'));
            $stmt->execute();
        } else if($weekMode === "2") {
            // 1週間以内に開催
            $stmt = $pdo->prepare($getHostListOneWeek);

            $stmt->bindValue(':current_date_ymd', date('Ymd'));
            $stmt->bindValue(':to_date_ymd', getDateYmdAfterOneWeek());
            $stmt->execute();
        }
    }

} catch(RuntimeException $e) {
    error_log($e, 0);
    header($msg_http_404_error001);
    exit(0);
} catch(Exception $e) {
    header($msg_http_500_error001);
    exit(0);
}

$firstFlag = TRUE;
$dataCnt = 0;
foreach($stmt as $row) {
    $holdingDateYmd = $row['holding_date_ymd'];
    if($firstFlag) {
        $nextHoldingDateYmd = $holdingDateYmd;
        $firstFlag = FALSE;
    }
    if($holdingDateYmd !== $nextHoldingDateYmd) {
        $hostInfoList[] = array(
            'holdingDateYmd'=>$holdingDateYmd,
            'hostList'=>$hostList
        );
        $hostList = NULL;
        $nextHoldingDateYmd = $holdingDateYmd;
    }

    $host = array();
    $host[] = array(
        'hostGroupId'=>$row['host_group_id'],
        'hostName'=>$row['host_name'],
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

if($dataCnt ===0) {
    returnJson(getErrorMessageArray($msg_hostlistApi_data_error001));
}
returnJson($hostInfoList);

?>