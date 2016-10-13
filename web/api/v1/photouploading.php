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
    mkdir("$uploadFolder", 0777);
}

$extension = pathinfo($fileName)["extension"];

$fileName = getDateYmd() . '_' . $branchPersonId . '_' . date('His') . '.' . $extension;
$reductionFilename = getDateYmd() . '_' . $branchPersonId . '_' . date('His') . '_reduction.' . $extension;
$thumbnailFilename = getDateYmd() . '_' . $branchPersonId . '_' . date('His') . '_thumbnail.' . $extension;
$uploadFilePath = $uploadFolder . '/' . $fileName;
$uploadReductionFilePath = $uploadFolder . '/' . $reductionFilename;
$uploadThumbnailFilePath = $uploadFolder . '/' . $thumbnailFilename;

// ファイル保存
if(mb_strtolower($extension) === 'jpg' || mb_strtolower($extension) === 'jpeg') {
    // jpg,jpeg
    imagejpeg($photo, $uploadFilePath);
} else {
    // png
    imagealphablending($photo, false);
    imagesavealpha($photo, true);
    imagepng($photo, $uploadFilePath);
}

list($originalWidth, $originalHeight)  = getimagesize($uploadFilePath);

$proportion = $originalWidth / $originalHeight;

// スマホ向け画像保存
$reductionWidth = 640;
if($proportion >= 1) {
    $reductionHeight = $reductionWidth / $proportion;
} else {
    $reductionHeight = $reductionWidth;
    $reductionWidth = $reductionWidth * $proportion;
}
$reductionPhoto = ImageCreateTrueColor($reductionWidth, $reductionHeight);
ImageCopyResampled($reductionPhoto, $photo, 0, 0, 0, 0, $reductionWidth, $reductionHeight, $originalWidth, $originalHeight);

if(mb_strtolower($extension) === 'jpg' || mb_strtolower($extension) === 'jpeg') {
    // jpg,jpeg
    imagejpeg($reductionPhoto, $uploadReductionFilePath);
} else {
    // png
    imagealphablending($reductionPhoto, false);
    imagesavealpha($reductionPhoto, true);
    imagepng($reductionPhoto, $uploadReductionFilePath);
}

// サムネイル画像保存
$thumbnailPhoto = ImageCreateTrueColor(180, 180);
if($proportion >= 1) {
    $originalStartX = $originalWidth / 2 - $originalWidth / $proportion / 2;
    ImageCopyResampled($thumbnailPhoto, $photo, 0, 0, $originalStartX, 0, 180, 180, $originalHeight, $originalHeight);
} else {
    $originalStartY = $originalHeight / 2 - $originalHeight * $proportion / 2;
    ImageCopyResampled($thumbnailPhoto, $photo, 0, 0, 0, $originalStartY, 180, 180, $originalWidth, $originalWidth);
}

if(mb_strtolower($extension) === 'jpg' || mb_strtolower($extension) === 'jpeg') {
    // jpg,jpeg
    imagejpeg($thumbnailPhoto, $uploadThumbnailFilePath);
} else {
    // png
    imagealphablending($thumbnailPhoto, false);
    imagesavealpha($thumbnailPhoto, true);
    imagepng($thumbnailPhoto, $uploadThumbnailFilePath);
}

imagedestroy($photo);
imagedestroy($reductionPhoto);
imagedestroy($thumbnailPhoto);

try {
    $pdo = createDbo();

    $ymdhis = getDateYmdHis();

    $stmt = $pdo->prepare($QUERY_INS_PHOTO);
    $stmt->bindValue(':photo_type_division', PHOTO_TYPE_DIVISION_HOLDINGDATE, PDO::PARAM_STR);
    $stmt->bindValue(':filepath', UPLOAD_DIR_HOLDGINDATE . $holdingDateYmd . '/', PDO::PARAM_STR);
    $stmt->bindValue(':filename', $fileName, PDO::PARAM_STR);
    $stmt->bindValue(':reduction_filename', $reductionFilename, PDO::PARAM_STR);
    $stmt->bindValue(':thumbnail_filename', $thumbnailFilename, PDO::PARAM_STR);
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