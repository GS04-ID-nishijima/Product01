<?php

$getLatestPhotoList =
    "
    SELECT
        UI.host_group_id        AS  host_group_id
    ,   US.name                 AS  host_group_name
    ,   UI.branch_person_id     AS  branch_person_id
    ,   US2.name                AS  branch_person_name
    ,   PH.filepath             AS  filepath
    ,   PH.filename             AS  filename
    ,   PH.reduction_filename   AS  reduction_filename
    ,   PH.thumbnail_filename   AS  thumbnail_filename
    ,   PH.comment              AS  comment
    FROM
        UPLOAD_INFO     AS  UI
        INNER JOIN      PHOTO   AS  PH  ON  (UI.photo_id            = PH.photo_id)
        INNER JOIN      USER    AS  US  ON  (UI.host_group_id       = US.id)
        LEFT JOIN       USER    AS  US2 ON  (UI.branch_person_id    = US2.id)
    ORDER BY
        UI.upload_date_ymd  DESC
    ,   UI.upload_date_time DESC
    LIMIT 50
    ";


$getHoldingdateYmdPhotlistBase =
    "
    SELECT
        UI.branch_person_id     AS  branch_person_id
    ,   US.name                 AS  branch_person_name
    ,   PH.filepath             AS  filepath
    ,   PH.filename             AS  filename
    ,   PH.reduction_filename   AS  reduction_filename
    ,   PH.thumbnail_filename   AS  thumbnail_filename
    ,   PH.comment              AS  comment
    ";

$getHoldingdateYmdPhotlistSortBase =
    "
    ORDER BY
        UI.branch_person_id
    ,   UI.upload_date_ymd  DESC
    ,   UI.upload_date_time DESC
    ";

$getHoldingdateYmdPhotlistHostGroup =
    $getHoldingdateYmdPhotlistBase .
    "
    FROM
        UPLOAD_INFO     AS  UI
        INNER JOIN      PHOTO   AS  PH ON
            (
                UI.holding_date_ymd     = :holdingDateYmd
            AND UI.host_group_id        = :hostGroupId
            AND UI.photo_id             = PH.photo_id
            )
        LEFT JOIN       USER    AS  US ON  (UI.branch_person_id    = US.id)
    "
    . $getHoldingdateYmdPhotlistSortBase;

$getHoldingdateYmdPhotlistBranchPerson =
    $getHoldingdateYmdPhotlistBase .
    "
    FROM
        UPLOAD_INFO     AS  UI
        INNER JOIN      PHOTO   AS  PH ON
            (
                UI.holding_date_ymd     = :holdingDateYmd
            AND UI.host_group_id        = :hostGroupId
            AND UI.branch_person_id     = :branchPersonId
            AND UI.photo_id             = PH.photo_id
            )
        INNER JOIN      USER    AS  US ON  (UI.branch_person_id    = US.id)
    "
    . $getHoldingdateYmdPhotlistSortBase;


?>