<?php

include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 写真のアップロード
 *
 * @param uploadphoto_holdingdate: 開催日
 * @param uploadphoto_hostgroup_id: 開催団体ID
 * @param uploadphoto_branchperson_id: 出店者ID
 * @param hostgroup_uploadphoto_comment: コメント
 * @param upload_photo_form: 写真
 * @return result
 *
 * @author nishijima
 **/

//foreach (getallheaders() as $name => $value) {
//    error_log("$name" . ':' . "$value");
//}

$holdingDateYmd = (string)filter_input(INPUT_POST, 'uploadphoto_holdingdate');
$hostGroupId = (int)filter_input(INPUT_POST, 'uploadphoto_hostgroup_id');
$branchPersonId = (int)filter_input(INPUT_POST, 'uploadphoto_branchperson_id');
$comment = (string)filter_input(INPUT_POST, 'hostgroup_uploadphoto_comment');

// 必須チェック
if(empty($holdingDateYmd) || empty($hostGroupId) || ($branchPersonId !== 0 && empty($branchPersonId)) || empty($comment)) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_PHOTOUPLOADING_API_PARAMETER_ERROR001));
}

// アップロードステータスチェック
if($_FILES['upload_photo_form']['error'] !== UPLOAD_ERR_OK) {
    error_log('UPLOAD_ERR_CD:' . $_FILES['upload_photo_form']['error']);
    exitWithErrorAsJson(getErrorMessageArray(MSG_PHOTOUPLOADING_API_PARAMETER_ERROR002));
}

// 必須チェック(ファイル)
if(!is_uploaded_file($_FILES["upload_photo_form"]["tmp_name"])) {
    exitWithErrorAsJson(getErrorMessageArray(MSG_PHOTOUPLOADING_API_PARAMETER_ERROR003));
}


$uploadFolder = __DIR__ . '/../../' . UPLOAD_DIR_HOLDGINDATE . $holdingDateYmd;

if(!file_exists ($uploadFolder)) {
    mkdir("$uploadFolder", 0644);
}

$fileName = getDateYmd() . '_' . $branchPersonId . '_' . date('His') . '.' . pathinfo($_FILES["upload_photo_form"]["name"])["extension"];
$uploadFilePath = $uploadFolder . '/' . $fileName;

move_uploaded_file($_FILES["upload_photo_form"]["tmp_name"], $uploadFilePath);

try {
    $pdo = createDbo();

    $stmt = $pdo->prepare($QUERY_INS_PHOTO);
    $stmt->bindValue(':photo_type_division', PHOTO_TYPE_DIVISION_HOLDINGDATE, PDO::PARAM_STR);
    $stmt->bindValue(':filepath', UPLOAD_DIR_HOLDGINDATE . $holdingDateYmd . '/', PDO::PARAM_STR);
    $stmt->bindValue(':filename', $fileName, PDO::PARAM_STR);
    $stmt->bindValue(':reduction_filename', '', PDO::PARAM_STR);
    $stmt->bindValue(':thumbnail_filename', '', PDO::PARAM_STR);
    $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);

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

?>