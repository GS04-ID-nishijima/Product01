<?php

include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催団体、出店者の開催日（出店日）リストを返す
 *
 * @param userType: 1:開催団体、2:出店者
 * @param mode: 1:当日から未来分(5日分)、2:当日から過去分(5日分)
 * @param id: 開催団体ID、出店者ID
 * @param onlyPhotoDateFlag: 写真掲載済み日付のみフラグ(デフォルトOFF、過去モード時のみ使用)
 * @return holdingDateYmdList
 *             holdingDateYmd
 *
 * @author nishijima
 **/
$mode = (string)filter_input(INPUT_GET, 'mode');
$userType = (string)filter_input(INPUT_GET, 'userType');
$id = (int)filter_input(INPUT_GET, 'id');
$onlyPhotoDateFlag = (string)filter_input(INPUT_GET, 'onlyPhotoDateFlag');

define("MODE_FUTURE", "1");
define("MODE_PAST", "2");

// 必須チェック
if(empty($userType) || empty($mode) || empty($id)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR001));
}

// パラメータuserTypeの値チェック
if($userType !== USERTYPE_HOSTGROUP && $userType !== USERTYPE_BRANCHPERSON){
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR002));
}

// パラメータmodeの値チェック
if($mode !== MODE_FUTURE && $mode !== MODE_PAST){
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR003));
}

try {
    $pdo = createDbo();
    $stmt = NULL;

    if($userType === USERTYPE_HOSTGROUP) {
        // 開催団体
        if($mode === MODE_FUTURE) {
            // 当日から未来分(5日分)
            $stmt = $pdo->prepare($queryHoldingDateYmdListHostGroupFuture);
        } else if($mode === MODE_PAST) {
            // 当日から過去分(5日分)
            if($onlyPhotoDateFlag === FLAG_ON) {
                $stmt = $pdo->prepare($queryHoldingDateYmdListHostGroupPastPhotoDate);
            } else {
                $stmt = $pdo->prepare($queryHoldingDateYmdListHostGroupPast);
            }
        }
        $stmt->bindValue(':host_group_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
        $stmt->execute();
    } else if($userType === USERTYPE_BRANCHPERSON) {
        // 出店者
        if($mode === MODE_FUTURE) {
            // 当日から未来分(5日分)
            $stmt = $pdo->prepare($queryHoldingDateYmdListBranchPersonFuture);
        } else if($mode === MODE_PAST) {
            // 当日から過去分(5日分)
            if($onlyPhotoDateFlag === FLAG_ON) {
                $stmt = $pdo->prepare($queryHoldingDateYmdListBranchPersonPastPhotoDate);
            } else {
                $stmt = $pdo->prepare($queryHoldingDateYmdListBranchPersonPast);
            }
        }
        $stmt->bindValue(':branch_person_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
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

$holdingDateYmdList = NULL;

foreach($stmt as $row) {
    $keys = array_keys($row);
    $tempArray = array();

    foreach($keys as $key) {
        $tempArray[toCamelCase($key)] = $row[$key];
    }
    $holdingDateYmdList[] = $tempArray;
}

if(count($holdingDateYmdList) === 0) {
    $holdingDateYmdList = array();
    exitAsJson($holdingDateYmdList);
}

$returnList = array(
    'holdingDateYmdList'=>$holdingDateYmdList
);

exitAsJson($returnList);

?>