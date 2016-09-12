<?php

include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 写真のアップロード
 *
 * @param holdingdate: 開催日
 * @param hostgroupId: 開催団体ID
 * @param branchpersonId: 出店者ID
 * @param photoComment: コメント
 * @param photoFileName: コメント
 * @param photo: 写真
 * @return result
 *
 * @author nishijima
 **/

$holdingDateYmd = (string)filter_input(INPUT_POST, 'holdingdate');
$hostGroupId = (int)filter_input(INPUT_POST, 'hostgroupId');
$branchPersonId = (int)filter_input(INPUT_POST, 'branchpersonId');
$comment = (string)filter_input(INPUT_POST, 'photoComment');
$fileName = (string)filter_input(INPUT_POST, 'photoFileName');
$encodePhoto = (string)filter_input(INPUT_POST, 'photo');


// 必須チェック
if(empty($holdingDateYmd) || empty($hostGroupId) || ($branchPersonId !== 0 && empty($branchPersonId)) || empty($comment) || empty($fileName) || empty($encodePhoto)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_PHOTOUPLOADING_API_PARAMETER_ERROR001));
}

$photo = imagecreatefromstring(base64_decode(preg_replace("/data:[^,]+,/i","",$encodePhoto)));
$uploadFolder = __DIR__ . '/../../' . UPLOAD_DIR_HOLDGINDATE . $holdingDateYmd;

if(!file_exists ($uploadFolder)) {
    mkdir("$uploadFolder", 0644);
}

$fileName = getDateYmd() . '_' . $branchPersonId . '_' . date('His') . '.' . pathinfo($fileName)["extension"];
$uploadFilePath = $uploadFolder . '/' . $fileName;

imagesavealpha($photo, TRUE);
imagepng($photo, $uploadFilePath);

try {
    $pdo = createDbo();

    $ymdhis = getDateYmdHis();

    $stmt = $pdo->prepare($QUERY_INS_PHOTO);
    $stmt->bindValue(':photo_type_division', PHOTO_TYPE_DIVISION_HOLDINGDATE, PDO::PARAM_STR);
    $stmt->bindValue(':filepath', UPLOAD_DIR_HOLDGINDATE . $holdingDateYmd . '/', PDO::PARAM_STR);
    $stmt->bindValue(':filename', $fileName, PDO::PARAM_STR);
    $stmt->bindValue(':reduction_filename', '', PDO::PARAM_STR);
    $stmt->bindValue(':thumbnail_filename', '', PDO::PARAM_STR);
    $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindValue(':ins_date', $ymdhis, PDO::PARAM_STR);
    $stmt->bindValue(':upd_date', $ymdhis, PDO::PARAM_STR);

    $insResultFlag = $stmt->execute();

    if($insResultFlag === false) {
        error_log($stmt->errorInfo());
        exitWithErrorAsJson(getErrorMessageArray(MSG_PHOTOUPLOADING_API_PARAMETER_ERROR003));
    } else {
        $photoId = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare($QUERY_INS_UPLOAD_INFO);
    $stmt->bindValue(':holding_date_ymd', $holdingDateYmd, PDO::PARAM_STR);
    $stmt->bindValue(':host_group_id', $hostGroupId, PDO::PARAM_STR);
    $stmt->bindValue(':photo_id', $photoId, PDO::PARAM_STR);
    if($branchPersonId === 0) {
        $stmt->bindValue(':branch_person_id', null, PDO::PARAM_STR);
    } else {
        $stmt->bindValue(':branch_person_id', $branchPersonId, PDO::PARAM_STR);
    }
    $stmt->bindValue(':upload_user_id', null, PDO::PARAM_STR);
    $stmt->bindValue(':upload_date_ymd', getDateYmd(), PDO::PARAM_STR);
    $stmt->bindValue(':upload_date_time', date('His'), PDO::PARAM_STR);
    $stmt->bindValue(':ins_date', $ymdhis, PDO::PARAM_STR);
    $stmt->bindValue(':upd_date', $ymdhis, PDO::PARAM_STR);

    $insResultFlag = $stmt->execute();

    if($insResultFlag === false) {
        error_log($stmt->errorInfo());
        header(MSG_HTTP_400_ERROR001);
        exit(0);
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

exitAsJson(array());

?>