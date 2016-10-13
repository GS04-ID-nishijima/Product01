<?php

include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催団体、出店者の開催日（出店日）リストを返す
 *
 * @param userType: 1:開催団体、2:出店者
 * @param mode: 1:当日から未来分(5日分)、2:当日から過去分(5日分)、3:写真（全て）
 * @param id: 開催団体ID、出店者ID
 * @return holdingDateYmdList
 *             holdingDateYmd
 *
 * @author nishijima
 **/
$mode = (string)filter_input(INPUT_GET, 'mode');
$userType = (string)filter_input(INPUT_GET, 'userType');
$id = (int)filter_input(INPUT_GET, 'id');

define("MODE_FUTURE", "1");
define("MODE_PAST", "2");
define("MODE_PHOTO", "3");

// 必須チェック
if(empty($userType) || empty($mode) || empty($id)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR001));
}

// パラメータuserTypeの値チェック
if($userType !== USERTYPE_HOSTGROUP && $userType !== USERTYPE_BRANCHPERSON){
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR002));
}

// パラメータmodeの値チェック
if($mode !== MODE_FUTURE && $mode !== MODE_PAST && $mode !== MODE_PHOTO){
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR003));
}

try {
    $pdo = createDbo();
    $stmt = NULL;

    if($userType === USERTYPE_HOSTGROUP) {
        // 開催団体
        if($mode === MODE_FUTURE) {
            // 当日から未来分(5日分)
            $stmt = $pdo->prepare($QUERY_HOLDINGDATEYMDLIST_HOSTGROUP_FUTURE);
            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
        } else if($mode === MODE_PAST) {
            // 当日から過去分(5日分)
            $stmt = $pdo->prepare($QUERY_HOLDINGDATEYMDLIST_HOSTGROUP_PAST);
            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
        } else if($mode === MODE_PHOTO) {
            // 写真のみ
            $stmt = $pdo->prepare($QUERY_HOLDINGDATEYMDLIST_HOSTGROUP_PHOTODATE);
        }
        $stmt->bindValue(':host_group_id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } else if($userType === USERTYPE_BRANCHPERSON) {
        // 出店者
        if($mode === MODE_FUTURE) {
            // 当日から未来分(5日分)
            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt = $pdo->prepare($QUERY_HOLDINGDATEYMDLIST_BRANCHPERSON_FUTURE);
        } else if($mode === MODE_PAST) {
            // 当日から過去分(5日分)
            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt = $pdo->prepare($QUERY_HOLDINGDATEYMDLIST_BRANCHPERSON_PAST);
        } else if($mode === MODE_PHOTO) {
            // 写真のみ
            $stmt = $pdo->prepare($QUERY_HOLDINGDATEYMDLIST_BRANCHPERSON_PAST_PHOTODATE);
        }
        $stmt->bindValue(':branch_person_id', $id, PDO::PARAM_INT);
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