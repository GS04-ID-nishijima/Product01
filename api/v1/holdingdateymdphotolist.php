<?php

include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催日を指定して写真リストを返す
 *
 * @param userType: 1:開催団体、2:出店者
 * @param holdingDateYmd: 開催日
 * @param hostGroupId: 開催団体ID
 * @param branchPersonId: 出店者ID
 * @return holdingDateYmdPhotoList
 *             branchPersonId
 *             branchPersonName
 *             photoList
 *                 filepath
 *                 filename
 *                 reductionFilename
 *                 thumbnailFilename
 *                 comment
 *
 * @author nishijima
 **/
$userType = (string)filter_input(INPUT_GET, 'userType');
$holdingDateYmd = (string)filter_input(INPUT_GET, 'holdingDateYmd');
$hostGroupId = (int)filter_input(INPUT_GET, 'hostGroupId');
$branchPersonId = (int)filter_input(INPUT_GET, 'branchPersonId');

// 必須チェック
if(empty($userType) || empty($holdingDateYmd) || empty($hostGroupId)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDPHOTOLISTAPI_PARAMETER_ERROR001));
}

// パラメータuserTypeeの値チェック
if($userType !== USERTYPE_HOSTGROUP && $userType !== USERTYPE_BRANCHPERSON){
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDPHOTOLISTAPI_PARAMETER_ERROR002));
}

// 出店者モードの場合は、出店者IDがセットされていることをチェック
if($userType === USERTYPE_BRANCHPERSON && (empty($branchPersonId))) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_HOLDINGDATEYMDPHOTOLISTAPI_PARAMETER_ERROR003));
}

try {
    $pdo = createDbo();
    $stmt = NULL;

    if($userType === USERTYPE_HOSTGROUP) {
        // 開催団体モード
        $stmt = $pdo->prepare($GET_HOLDINGDATEYMDPHOTOLIST_HOSTGROUP);

        $stmt->bindValue(':holdingDateYmd', $holdingDateYmd, PDO::PARAM_STR);
        $stmt->bindValue(':hostGroupId', $hostGroupId, PDO::PARAM_INT);
        $stmt->execute();
    } else if($userType === USERTYPE_BRANCHPERSON) {
        // 出店者モード
        $stmt = $pdo->prepare($GET_HOLDINGDATEYMDPHOTOLIST_BRANCHPERSON);

        $stmt->bindValue(':holdingDateYmd', $holdingDateYmd, PDO::PARAM_STR);
        $stmt->bindValue(':hostGroupId', $hostGroupId, PDO::PARAM_INT);
        $stmt->bindValue(':branchPersonId', $branchPersonId, PDO::PARAM_INT);
        $stmt->execute();
    }

} catch(RuntimeException $e) {
    error_log($e, 0);
    header(MSG_HTTP_400_ERROR001);
    exit(0);
} catch(Exception $e) {
    header(MSG_HTTP_500_ERROR001);
    exit(0);
}

$firstFlag = TRUE;
$branchPersonId = NULL;
$branchPersonName = NULL;
$holdingDateYmdPhotoList =NULL;

foreach($stmt as $row) {
    $nextBranchPersonId = $row['branch_person_id'];
    $nextBranchPersonName = $row['branch_person_name'];
    if($firstFlag) {
        $branchPersonId = $nextBranchPersonId;
        $branchPersonName = $nextBranchPersonName;
        $firstFlag = FALSE;
    }
    if($branchPersonId !== $nextBranchPersonId) {
        $holdingDateYmdPhotoList[] = array(
            'branchPersonId'=>$branchPersonId,
            'branchPersonName'=>$branchPersonName,
            'photoList'=>$photoList
        );
        $photoList = NULL;
        $branchPersonId = $nextBranchPersonId;
        $branchPersonName = $nextBranchPersonName;
    }

    $keys = array_keys($row);
    $tempArray = array();

    foreach($keys as $key) {
        $tempArray[toCamelCase($key)] = $row[$key];
    }
    unset($tempArray['branchPersonId']);
    unset($tempArray['branchPersonName']);

    $photoList[] = $tempArray;
}

// 取得データが0件の場合
if(count($holdingDateYmdPhotoList) === 0) {
    $holdingDateYmdPhotoList = array();
    exitAsJson($holdingDateYmdPhotoList);
} else {
    $holdingDateYmdPhotoList[] = array(
        'branchPersonId'=>$branchPersonId,
        'branchPersonName'=>$branchPersonName,
        'photoList'=>$photoList
    );
}

$returnList = array(
    'holdingDateYmdPhotoList'=>$holdingDateYmdPhotoList
);

exitAsJson($returnList);

?>