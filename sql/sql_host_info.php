<?php

$queryHoldingDateYmdListBase =
    "
    SELECT
        DISTINCT
        HI.holding_date_ymd     AS holding_date_ymd
    FROM
        HOST_INFO       HI
    ,   USER            US
    ";

$queryHoldingDateYmdListSortBaseFutre =
    "
    ORDER BY
        HI.holding_date_ymd
    LIMIT 5
    ";

$queryHoldingDateYmdListSortBasePast =
    "
    ORDER BY
        HI.holding_date_ymd DESC
    LIMIT 5
    ";

$queryHoldingDateYmdListHostGroupFuture =
    $queryHoldingDateYmdListBase .
    "
    WHERE
        HI.host_group_id    = :host_group_id
    AND HI.holding_date_ymd >= :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    " .
    $queryHoldingDateYmdListSortBaseFutre;

$queryHoldingDateYmdListHostGroupPast =
    $queryHoldingDateYmdListBase .
    "
    WHERE
        HI.host_group_id    = :host_group_id
    AND HI.holding_date_ymd <= :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    " .
    $queryHoldingDateYmdListSortBasePast;

$queryHoldingDateYmdListHostGroupPastPhotoDate =
    $queryHoldingDateYmdListBase .
    "
    ,   UPLOAD_INFO UI
    WHERE
        HI.host_group_id    = :host_group_id
    AND HI.holding_date_ymd <= :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    AND HI.holding_date_ymd = UI.holding_date_ymd
    AND HI.host_group_id    = UI.host_group_id
    " .
    $queryHoldingDateYmdListSortBasePast;

$queryHoldingDateYmdListBranchPersonFuture =
    $queryHoldingDateYmdListBase .
    "
    WHERE
        HI.branch_person_id =   :branch_person_id
    AND HI.holding_date_ymd >=  :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    " .
    $queryHoldingDateYmdListSortBaseFutre;

$queryHoldingDateYmdListBranchPersonPast =
    $queryHoldingDateYmdListBase .
    "
    WHERE
        HI.branch_person_id =   :branch_person_id
    AND HI.holding_date_ymd <=  :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    " .
    $queryHoldingDateYmdListSortBasePast;

$queryHoldingDateYmdListBranchPersonPastPhotoDate =
    $queryHoldingDateYmdListBase .
    "
    ,   UPLOAD_INFO UI
    WHERE
        HI.branch_person_id =   :branch_person_id
    AND HI.holding_date_ymd <=  :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    AND HI.holding_date_ymd = UI.holding_date_ymd
    AND HI.branch_person_id = UI.branch_person_id
    " .
    $queryHoldingDateYmdListSortBasePast;

?>