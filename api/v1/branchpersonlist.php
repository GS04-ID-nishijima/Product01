<?php

include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催日ごとの出店者リストを返す
 *
 * @param mode: 1:日付指定、2:未来分のみ（当日含む）、3:すべて
 * @param hostGroupId: 開催団体ID
 * @param holdingDateYmd: 開催日
 * @return holdingDateBranchPersonList
 *             holdingDateYmd
 *             branchPersonList
 *                 branchPersonId
 *                 branchPersonName
 *
 * @author nishijima
 **/
$mode = (string)filter_input(INPUT_GET, 'mode');
$hostGroupId = (int)filter_input(INPUT_GET, 'hostGroupId');
$holdingDateYmd = (string)filter_input(INPUT_GET, 'holdingDateYmd');

define("MODE_APPOINT", "1");
define("MODE_FUTURE", "2");
define("MODE_ALL", "3");

// 必須チェック
if(empty($mode) || empty($hostGroupId)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_BRANCHPERSONLISTAPI_PARAMETER_ERROR001));
}

// パラメータmodeの値チェック
if($mode !== MODE_APPOINT && $mode !== MODE_FUTURE && $mode !== MODE_ALL){
    exitWithErrorAsJson(getErrorMessageArray(MSG_BRANCHPERSONLISTAPI_PARAMETER_ERROR002));
}

// モードが日付指定の場合の開催日必須チェック
if($mode === MODE_APPOINT && empty($holdingDateYmd)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_BRANCHPERSONLISTAPI_PARAMETER_ERROR003));
}

try {
    $pdo = createDbo();
    $stmt = NULL;

    if($mode === MODE_APPOINT) {
        $stmt = $pdo->prepare($QUERY_BRANCHPERSONLIST_APPOINT);
        $stmt->bindValue(':host_group_id', $hostGroupId, PDO::PARAM_INT);
        $stmt->bindValue(':holding_date_ymd', $holdingDateYmd, PDO::PARAM_STR);
        $stmt->bindValue(':host_group_date', $holdingDateYmd, PDO::PARAM_STR);
        $stmt->bindValue(':host_group_name', '開催者', PDO::PARAM_STR);
        $stmt->execute();
    }else if($mode === MODE_FUTURE) {
        $stmt = $pdo->prepare($QUERY_BRANCHPERSONLIST_FUTURE);
        $stmt->bindValue(':host_group_id', $hostGroupId, PDO::PARAM_INT);
        $stmt->bindValue(':holding_date_ymd', getDateYmd(), PDO::PARAM_STR);
        $stmt->execute();
    }else if($mode === MODE_ALL) {
        $stmt = $pdo->prepare($QUERY_BRANCHPERSONLIST_ALL);
        $stmt->bindValue(':host_group_id', $hostGroupId, PDO::PARAM_INT);
        $stmt->execute();
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
$holdingDateBranchPersonList = NULL;
$branchPersonList = NULL;
foreach($stmt as $row) {
    $nextHoldingDateYmd = $row['holding_date_ymd'];
    if($firstFlag) {
        $holdingDateYmd = $nextHoldingDateYmd;
        $firstFlag = FALSE;
    }

    if($holdingDateYmd !== $nextHoldingDateYmd) {
        $holdingDateBranchPersonList[] = array(
            'holdingDateYmd'=>$holdingDateYmd,
            'branchPersonList'=>$branchPersonList
        );

        $branchPersonList = NULL;
        $holdingDateYmd = $nextHoldingDateYmd;
    }

    $keys = array_keys($row);
    $tempArray = array();

    foreach($keys as $key) {
        $tempArray[toCamelCase($key)] = $row[$key];
    }
    unset($tempArray['holdingDateYmd']);

    $branchPersonList[] = $tempArray;
}

// 取得データが0件の場合
if(count($holdingDateBranchPersonList) === 0 && count($branchPersonList) === 0) {
    $holdingDateBranchPersonList = array();
    exitAsJson($holdingDateBranchPersonList);
} else {
    $holdingDateBranchPersonList[] = array(
        'holdingDateYmd'=>$holdingDateYmd,
        'branchPersonList'=>$branchPersonList
    );
}

$returnList = array(
    'holdingDateBranchPersonList'=>$holdingDateBranchPersonList
);

exitAsJson($returnList);

?>