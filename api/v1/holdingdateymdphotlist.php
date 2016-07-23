<?php

include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催日を指定して写真リストを返す
 *
 * @param userTyep: 1:開催団体、2:出店者
 * @param holdingDateYmd: 開催日
 * @param hostGroupId: 開催団体ID
 * @param branchPersonId: 出店者ID
 * @return photoList
 *             branchPersonId
 *             branchPersonName
 *             photoList
 *                 photoId
 *                 filepath
 *                 filename
 *                 reductionFilename
 *                 thumbnailFilename
 *                 comment
 *                 eventName
 *
 * @author nishijima
 **/
$userType = (string)filter_input(INPUT_GET, 'userType');
$holdingDateYmd = (string)filter_input(INPUT_GET, 'holdingDateYmd');
$hostGroupId = (int)filter_input(INPUT_GET, 'hostGroupId');
$branchPersonId = (int)filter_input(INPUT_GET, 'branchPersonId');

// 必須チェック
if(empty($userType) || empty($holdingDateYmd) || empty($hostGroupId)) {
    returnJson(getErrorMessageArray($msg_holdingdateymdphotolistApi_parameter_error001));
}

// パラメータuserTyepeの値チェック
if($userType !== '1' && $userType !== '2'){
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error003));
}

// 出店者モードの場合は、出店者IDがセットされていることをチェック
if($userType === '2' && (empty($branchPersonId))) {
    returnJson(getErrorMessageArray($msg_holdingdateymdphotolistApi_parameter_error003));
}

try {
    $pdo = createDbo();
    $stmt = NULL;

    if($userType === '1') {
        // 開催団体モード
        $stmt = $pdo->prepare($getHoldingdateYmdPhotlistHostGroup);

        $stmt->bindValue(':holdingDateYmd', $holdingDateYmd, PDO::PARAM_STR);
        $stmt->bindValue(':hostGroupId', $hostGroupId, PDO::PARAM_INT);
        $stmt->execute();
    } else if($userType === "2") {
        // 出店者モード
        $stmt = $pdo->prepare($getHoldingdateYmdPhotlistBranchPerson);

        $stmt->bindValue(':holdingDateYmd', $holdingDateYmd, PDO::PARAM_STR);
        $stmt->bindValue(':hostGroupId', $hostGroupId, PDO::PARAM_INT);
        $stmt->bindValue(':branchPersonId', $branchPersonId, PDO::PARAM_INT);
        $stmt->execute();
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
$branchPersonId = null;
$branchPersonName = null;

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

    $photo = array();
    $photo[] = array(
        'photoId'=>$row['photo_id'],
        'filepath'=>$row['filepath'],
        'filename'=>$row['filename'],
        'reductionFilename'=>$row['reduction_filename'],
        'thumbnailFilename'=>$row['thumbnail_filename'],
        'comment'=>$row['comment']
    );

    $photoList[] = $photo;
    $dataCnt += 1;
}

// 取得データが0件の場合
if($dataCnt ===0) {
    $holdingDateYmdPhotoList[] = array();
    returnJson($holdingDateYmdPhotoList);
} else {
    $holdingDateYmdPhotoList[] = array(
        'branchPersonId'=>$branchPersonId,
        'branchPersonName'=>$branchPersonName,
        'photoList'=>$photoList
    );
}
returnJson($holdingDateYmdPhotoList);

?>