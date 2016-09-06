<?php

include __DIR__ . '/../../include/message.php';
include __DIR__ . '/../../include/func.php';
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

    $stmt = $pdo->prepare($GET_LATESTPHOTOLIST);
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

$dataCnt = 0;
$photoList = NULL;
foreach($stmt as $row) {
    $keys = array_keys($row);
    $tempArray = array();

    foreach($keys as $key) {
        $tempArray[toCamelCase($key)] = $row[$key];
    }

    $photoList[] = $tempArray;
    $dataCnt += 1;
    if($dataCnt === $number) {
        break;
    }
}

if(count($photoList) === 0) {
    $photoList = array();
    exitAsJson($photoList);
}
exitAsJson($photoList);

?>