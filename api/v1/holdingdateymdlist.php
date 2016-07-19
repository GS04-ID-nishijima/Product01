<?php

include __DIR__ . '/../../include/func.php';
include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../sql/sql.php';

/**
 * 開催団体、出店者の開催日（出店日）リストを返す
 *
 * @param mode: 1:当日から未来分(5日分)、2:当日から過去分(5日分)
 * @param userTyep: 1:開催団体、2:出店者
 * @param id: 開催団体ID、出店者ID
 * @return holdingDateYmdList
 *             holdingDateYmd
 *
 * @author nishijima
 **/
$mode = (string)filter_input(INPUT_GET, 'mode');
$userTyep = (string)filter_input(INPUT_GET, 'userTyep');
$id = (int)filter_input(INPUT_GET, 'id');

// 必須チェック
if(empty($mode) || empty($userTyep) || empty($id)) {
    returnJson(getErrorMessageArray($msg_holdingdateymdlistApi_parameter_error001));
}

// パラメータmodeの値チェック
if($mode !== '1' && $mode !== '2'){
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error003));
}

// パラメータuserTypeの値チェック
if($userTyep !== '1' && $userTyep !== '2'){
    returnJson(getErrorMessageArray($msg_hostlistApi_parameter_error004));
}

try {
    $pdo = createDbo();

    $stmt = null

    if($userTyep === '1') {
        if($mode === '1') {
            
        } else if($mode === '2') {
            
        }
        $stmt = $pdo->prepare($getLatestPhotoList);
        $stmt->execute();
    } else if($userTyep === '2') {
        
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
}

if($dataCnt ===0) {
    returnJson(getErrorMessageArray($msg_api_data_error001));
}
returnJson($photoList);

?>