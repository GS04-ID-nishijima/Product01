<?php

include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催団体情報を返す
 *
 * @param hostGroupId: 開催団体ID
 * @return hostGroupName
 *         placeName
 *         holdingSchedule
 *         holdingTime
 *         latitude
 *         longitude
 *         branchScale
 *         directions
 *         formalHpUrl
 *         facebookUrl
 *         twitterUrl
 *
 * @author nishijima
 **/
$hostGroupId = (int)filter_input(INPUT_GET, 'hostGroupId');

// 必須チェック
if(empty($hostGroupId)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOSTGROUPINFOAPI_PARAMETER_ERROR001));
}

try {
    $pdo = createDbo();
    $stmt = $pdo->prepare($QUERY_HOSTGROUPINFO);
    $stmt->bindValue(':host_group_id', $hostGroupId, PDO::PARAM_STR);
    $stmt->execute();

} catch(RuntimeException $e) {
    error_log($e, 0);
    header(MSG_HTTP_400_ERROR001);
    exit(0);
} catch(Exception $e) {
    error_log($e, 0);
    header(MSG_HTTP_500_ERROR001);
    exit(0);
}

$row = $stmt->fetch(PDO::FETCH_ASSOC);

// 取得データが0件の場合
if(!$row) {
    $returnList = array();
    exitAsJson($returnList);
}

$keys = array_keys($row);
$returnList = array();

foreach($keys as $key) {
    $returnList[toCamelCase($key)] = $row[$key];
}

// 取得データが0件の場合
if(count($returnList) === 0) {
    $returnList[] = array();
    exitAsJson($returnList);
}

exitAsJson($returnList);

?>