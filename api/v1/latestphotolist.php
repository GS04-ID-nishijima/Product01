<?php

include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 最新の写真リストを返す
 *
 * @param number: 取得枚数指定、未設定の場合は最大50枚
 * @return photoList
 *             hostGroupId
 *             hostGroupName
 *             branchPersonId
 *             branchPersonName
 *             photoId
 *             filepath
 *             filename
 *             reductionFilename
 *             thumbnailFilename
 *             comment
 *
 * @author nishijima
 **/
$number = (int)filter_input(INPUT_GET, 'number');

try {
    $pdo = createDbo();

    $stmt = $pdo->prepare($getLatestPhotoList);
    $stmt->execute();

} catch(RuntimeException $e) {
    error_log($e, 0);
    header($msg_http_400_error001);
    exit(0);
} catch(Exception $e) {
    error_log($e, 0);
    header($msg_http_500_error001);
    exit(0);
}

$dataCnt = 0;
foreach($stmt as $row) {
    $photo = array();
    $photo[] = array(
        'hostGroupId'=>$row['host_group_id'],
        'hostGroupName'=>$row['host_group_name'],
        'branchPersonId'=>$row['branch_person_id'],
        'branchPersonName'=>$row['branch_person_name'],
        'photoId'=>$row['photo_id'],
        'filepath'=>$row['filepath'],
        'filename'=>$row['filename'],
        'reductionFilename'=>$row['reduction_filename'],
        'thumbnailFilename'=>$row['thumbnail_filename'],
        'comment'=>$row['comment']
    );

    $photoList[] = $photo;
    $dataCnt += 1;
    if($dataCnt === $number) {
        break;
    }
}

if($dataCnt ===0) {
    $photoList[] = array();
    returnJson($hostList);
}
returnJson($photoList);

?>