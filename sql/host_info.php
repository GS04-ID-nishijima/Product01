<?php

$queryHoldingDateYmdListBase =
    "
    SELECT
        DISTINCT
        HI.holding_date_ymd     AS holding_date_ymd
    ,   HI.host_group_id        AS host_group_id
    ,   US.name                 AS host_group_name
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

?>