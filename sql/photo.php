<?php

$getLatestPhotoList =
    "
    SELECT
        UI.host_group_id        AS  host_group_id
    ,   US.name                 AS  host_group_name
    ,   UI.branch_person_id     AS  branch_person_id
    ,   US2.name                AS  branch_person_name
    ,   UI.photo_id             AS  photo_id
    ,   PH.filepath             AS  filepath
    ,   PH.filename             AS  filename
    ,   PH.reduction_filename   AS  reduction_filename
    ,   PH.thumbnail_filename   AS  thumbnail_filename
    ,   PH.comment              AS  comment
    FROM
        UPLOAD_INFO     UI
        INNER JOIN          PHOTO           PH  ON  (UI.photo_id            =   PH.photo_id)
        INNER JOIN          USER            US  ON  (UI.host_group_id       =   US.id)
        LEFT OUTER JOIN     USER            US2 ON  (UI.branch_person_id    =   US2.id)
    WHERE
        US.user_type_division   =   '1'
    AND US2.user_type_division  =   '2'
    ORDER   BY
        UI.upload_date_ymd  DESC
    ,   UI.upload_date_time DESC
    LIMIT   50
    ";


$queryHoldingDateYmdPhotoListBase =
    "
    SELECT
    FROM
        HOST_GROUP      HG
    ,   HOST_INFO       HI
    ,   BRANCH_PERSON   BP
    ,   
    ";


$queryHostListBase = 
    "
    SELECT
        DISTINCT
        HI.holding_date_ymd     AS  holding_date_ymd
    ,   HG.host_group_id        AS  host_group_id
    ,   HG.host_group_name      AS  host_group_name
    ,   HG.place_name           AS  place_name
    ,   HG.holding_schedule     AS  holding_schedule
    ,   HG.holding_time         AS  holding_time
    ,   HG.latitude             AS  latitude
    ,   HG.longitude            AS  longitude
    ,   HG.branch_scale         AS  branch_scale
    FROM
        HOST_GROUP  HG
    ,   HOST_INFO   HI
    ";

//$queryHostListSortBase =
//    "
//    ORDER   BY
//        HI.holding_date_ymd
//    ,   HG.latitude DESC
//    ,   HG.longitude
//    ";
//
//$getHostListScopeMap =
//    $queryHostListBase .
//    "
//    WHERE
//        HG.host_group_id    =       HI.host_group_id
//    AND :current_date_ymd   <=      HI.holding_date_ymd
//    AND HG.latitude         BETWEEN :strLati AND :endLati
//    AND HG.longitude        BETWEEN :strLong AND :endLong
//    " . 
//    $queryHostListSortBase;
//
//$getHostListScopwMapOneWeek =
//    $queryHostListBase .
//    "
//    WHERE
//        HG.host_group_id    =       HI.host_group_id
//    AND :current_date_ymd   <=      HI.holding_date_ymd
//    AND HI.holding_date_ymd <=      :to_date_ymd
//    AND HG.latitude         BETWEEN :strLati AND :endLati
//    AND HG.longitude        BETWEEN :strLong AND :endLong
//    " . 
//    $queryHostListSortBase;
//
//$getHostList =
//    $queryHostListBase .
//    "
//    WHERE
//        HG.host_group_id    =       HI.host_group_id
//    AND :current_date_ymd   <=      HI.holding_date_ymd
//    " . 
//    $queryHostListSortBase;
//
//$getHostListOneWeek =
//    $queryHostListBase .
//    "
//    WHERE
//        HG.host_group_id    =       HI.host_group_id
//    AND :current_date_ymd   <=      HI.holding_date_ymd
//    AND HI.holding_date_ymd <=      :to_date_ymd
//    " . 
//    $queryHostListSortBase;

?>