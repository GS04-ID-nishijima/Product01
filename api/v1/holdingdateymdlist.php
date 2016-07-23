<?php

include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催団体、出店者の開催日（出店日）リストを返す
 *
 * @param userTyep: 1:開催団体、2:出店者
 * @param mode: 1:当日から未来分(5日分)、2:当日から過去分(5日分)
 * @param id: 開催団体ID、出店者ID
 * @return holdingDateYmdList
 *             holdingDateYmd
 *             hostGroupId
 *             hostGroupName
 *
 * @author nishijima
 **/
$mode = (string)filter_input(INPUT_GET, 'mode');
$userType = (string)filter_input(INPUT_GET, 'userTyep');
$id = (int)filter_input(INPUT_GET, 'id');

// 必須チェック
if(empty($userType) || empty($mode) || empty($id)) {
    returnErrorJson(getErrorMessageArray($msg_holdingdateymdlistApi_parameter_error001));
}

// パラメータuserTypeの値チェック
if($userType !== '1' && $userType !== '2'){
    returnErrorJson(getErrorMessageArray($msg_holdingdateymdlistApi_parameter_error003));
}

// パラメータmodeの値チェック
if($mode !== '1' && $mode !== '2'){
    returnErrorJson(getErrorMessageArray($msg_holdingdateymdlistApi_parameter_error002));
}

try {
    $pdo = createDbo();
    $stmt = null;

    if($userType === '1') {
        // 開催団体
        if($mode === '1') {
            // 当日から未来分(5日分)
            $stmt = $pdo->prepare($queryHoldingDateYmdListHostGroupFuture);

            $stmt->bindValue(':host_group_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt->execute();
        } else if($mode === '2') {
            // 当日から過去分(5日分)
            $stmt = $pdo->prepare($queryHoldingDateYmdListHostGroupPast);

            $stmt->bindValue(':host_group_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt->execute();
        }
    } else if($userType === '2') {
        // 出店者
        if($mode === '1') {
            // 当日から未来分(5日分)
            $stmt = $pdo->prepare($queryHoldingDateYmdListBranchPersonFuture);

            $stmt->bindValue(':branch_person_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
            $stmt->execute();
        } else if($mode === '2') {
            // 当日から過去分(5日分)
            $stmt = $pdo->prepare($queryHoldingDateYmdListBranchPersonPast);

            $stmt->bindValue(':branch_person_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':current_date_ymd', getDateYmd(), PDO::PARAM_STR);
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

$dataCnt = 0;
foreach($stmt as $row) {
    $holdingDateYmdList[] = array(
        'holdingDateYmd'=>$row['holding_date_ymd'],
        'hostGroupId'=>$row['host_group_id'],
        'hostGroupName'=>$row['host_group_name']
    );

    $dataCnt += 1;
}

if($dataCnt ===0) {
    $holdingDateYmdList[] = array();
    returnJson($holdingDateYmdList);
}
returnJson($holdingDateYmdList);

?>